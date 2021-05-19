<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanParamSignatureMismatch
// @phan-file-suppress PhanParamTooFew
// @phan-file-suppress PhanTypeMismatchArgument
// @phan-file-suppress PhanTypeMismatchArgumentReal
// @phan-file-suppress PhanUndeclaredClassMethod
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredVariable
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingReturn
// phpcs:disable MediaWiki.Commenting.FunctionComment.SpacingAfter
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\Util;
use Wikimedia\Dodo\Internal\WhatWG;
use Wikimedia\Zest\Zest;

/******************************************************************************
 * Element.php
 * -----------
 * Defines an "Element"
 */
/******************************************************************************
 *
 * Where a specification is implemented, the following annotations appear.
 *
 * DOM-1     W3C DOM Level 1 		     http://w3.org/TR/DOM-Level-1/
 * DOM-2     W3C DOM Level 2 Core	     http://w3.org/TR/DOM-Level-2-Core/
 * DOM-3     W3C DOM Level 3 Core	     http://w3.org/TR/DOM-Level-3-Core/
 * DOM-4     W3C DOM Level 4 		     http://w3.org/TR/dom/
 * DOM-LS    WHATWG DOM Living Standard      http://dom.spec.whatwg.org/
 * DOM-PS-WD W3C DOM Parsing & Serialization http://w3.org/TR/DOM-Parsing/
 * WEBIDL-1  W3C WebIDL Level 1	             http://w3.org/TR/WebIDL-1/
 * XML-NS    W3C XML Namespaces		     http://w3.org/TR/xml-names/
 * CSS-OM    CSS Object Model                http://drafts.csswg.org/cssom-view/
 * HTML-LS   HTML Living Standard            https://html.spec.whatwg.org/
 *
 */

/*
 * Qualified Names, Local Names, and Namespace Prefixes
 *
 * An Element or Attribute's qualified name is its local name if its
 * namespace prefix is null, and its namespace prefix, followed by ":",
 * followed by its local name, otherwise.
 */

class Element extends ContainerNode implements \Wikimedia\IDLeDOM\Element {
	// DOM mixins
	use ChildNode;
	use NonDocumentTypeChildNode;
	use ParentNode;
	use Slottable;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\Element;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Element;

	// XXX figure out how to save storage by only storing this for
	// HTMLUnknownElement etc; for specific HTML*Element subclasses
	// we should be able to get this from the object type.
	// Split Element into AbstractElement and Element, where only
	// element has these fields; then
	// HTMLElement extends HTMLAbstractElement extends AbstractElement
	// where HTMLElement has these fields; then every class which
	// maps 1:1 on a particular localName will extend HTMLAbstractElement
	// not HTMLElement.
	// _prefix is in AbstractElement (since even HTML interfaces may have
	//   arbitrary prefixes in some documents; this is harder to factor out)
	// _namespaceURI and _localName are in Element
	// _localName is in HTMLElement (since every HTML element hard codes the
	//   namespace)
	// _nodeName is always computed, never stored.

	/** @var ?string */
	private $_namespaceURI = null;
	/** @var ?string */
	private $_localName = null;
	/** @var ?string */
	private $_prefix = null;

	/**
	 * @var ?NamedNodeMap Attribute storage; null if no attributes
	 */
	public $_attributes = null;

	/**
	 * A registry of handlers for changes to specific attributes.
	 * @var array<string,callable>|null
	 */
	public static $_attributeChangeHandlers = null;

	public static function _attributeChangeHandlerFor( string $localName ) {
		if ( static::$_attributeChangeHandlers === null ) {
			static::$_attributeChangeHandlers = [
				"id" => static function ( $elem, $old, $new ) {
					if ( !$elem->getIsConnected() ) {
						return;
					}
					if ( $old !== null ) {
						$elem->_ownerDocument->_removeFromIdTable( $old, $elem );
					}
					if ( $new !== null ) {
						$elem->_ownerDocument->_addToIdTable( $new, $elem );
					}
				},
				"class" => static function ( $elem, $old, $new ) {
					if ( $elem->_classList !== null ) {
						$elem->_classList->_getList();
					}
				},
			];
		}
		return static::$_attributeChangeHandlers[$localName] ?? null;
	}

	/**
	 * @var ?DOMTokenList
	 */
	private $_classList = null;

	/**
	 * Element constructor
	 *
	 * @param Document $doc
	 * @param string $lname
	 * @param ?string $ns
	 * @param ?string $prefix
	 * @return void
	 */
	public function __construct( Document $doc, string $lname, ?string $ns, ?string $prefix = null ) {
		parent::__construct();

		/*
		 * DOM-LS: "Elements have an associated namespace, namespace
		 * prefix, local name, custom element state, custom element
		 * definition, is value. When an element is created, all of
		 * these values are initialized.
		 */
		$this->_namespaceURI  = $ns;
		$this->_prefix        = $prefix;
		$this->_localName     = $lname;
		$this->_ownerDocument = $doc;

		/*
		 * DOM-LS: "Elements also have an attribute list, which is
		 * a list exposed through a NamedNodeMap. Unless explicitly
		 * given when an element is created, its attribute list is
		 * empty."
		 */
		$this->_attributes = null; // save space if no attributes
	}

	/**********************************************************************
	 * ACCESSORS
	 */

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::ELEMENT_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		$prefix = $this->getPrefix();
		$lname = $this->getLocalName();
		/*
		 * DOM-LS: "An Element's qualified name is its local name
		 * if its namespace prefix is null, and its namespace prefix,
		 * followed by ":", followed by its local name, otherwise."
		 */
		$qname = ( $prefix === null ) ? $lname : ( $prefix . ':' . $lname );
		if ( $this->_isHTMLElement() ) {
			$qname = Util::toAsciiUppercase( $qname );
		}
		return $qname;
	}

	/**
	 * @return NamedNodeMap
	 */
	public function getAttributes() : NamedNodeMap {
		if ( $this->_attributes === null ) {
			$this->_attributes = new NamedNodeMap( $this );
		}
		return $this->_attributes;
	}

	public function getPrefix(): ?string {
		return $this->_prefix;
	}

	public function getLocalName(): string {
		return $this->_localName;
	}

	public function getNamespaceURI(): ?string {
		return $this->_namespaceURI;
	}

	final public function getTagName(): string {
		return $this->getNodeName();
	}

	/** @inheritDoc */
	public function getTextContent() : ?string {
		$text = [];
		Algorithm::descendant_text_content( $this, $text );
		return implode( "", $text );
	}

	/** @inheritDoc */
	public function setTextContent( ?string $value ) : void {
		$value = $value ?? '';
		$this->_removeChildren();
		if ( $value !== "" ) {
			/* Equivalent to Node:: appendChild without checks! */
			WhatWG::insert_before_or_replace( $node, $this->_ownerDocument->createTextNode( $value ), null );
		}
	}

	/**********************************************************************
	 * METHODS DELEGATED FROM NODE
	 */

	public function _subclass_cloneNodeShallow(): ?Node {
		/*
		 * XXX:
		 * Modify this to use the constructor directly or avoid
		 * error checking in some other way. In case we try
		 * to clone an invalid node that the parser inserted.
		 */
		if ( $this->getNamespaceURI() !== Util::NAMESPACE_HTML
			 || $this->getPrefix()
			 || !$this->getOwnerDocument()->_isHTMLDocument() ) {
			if ( $this->getPrefix() === null ) {
				$name = $this->getLocalName();
			} else {
				$name = $this->getPrefix() . ':' . $this->getLocalName();
			}
			$clone = $this->getOwnerDocument()->createElementNS(
				$this->getNamespaceURI(),
				$name
			);
		} else {
			$clone = $this->getOwnerDocument()->createElement(
				$this->getLocalName()
			);
		}
		'@phan-var Element $clone'; // @var Element $clone

		if ( $this->_attributes !== null ) {
			foreach ( $this->_attributes as $a ) {
				$clone->setAttributeNodeNS( $a->cloneNode() );
			}
		}

		return $clone;
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		if ( $this->getLocalName() !== $node->getLocalName()
			 || $this->getNamespaceURI() !== $node->getNamespaceURI()
			 || $this->getPrefix() !== $node->getPrefix()
			 || count( $this->_attributes ?? [] ) !== count( $node->_attributes ?? [] ) ) {
			return false;
		}

		/*
		 * Compare the sets of attributes, ignoring order
		 * and ignoring attribute prefixes.
		 */
		foreach ( ( $this->_attributes ?? [] ) as $a ) {
			if ( !$node->hasAttributeNS( $a->getNamespaceURI(), $a->getLocalName() ) ) {
				return false;
			}
			if ( $node->getAttributeNS( $a->getNamespaceURI(), $a->getLocalName() ) !== $a->getValue() ) {
				return false;
			}
		}
		return true;
	}

	/**********************************************************************
	 * ATTRIBUTE: get/set/remove/has/toggle
	 */

	/**
	 * Fetch the value of an attribute with the given qualified name
	 *
	 * param string $qname The attribute's qualifiedName
	 * return ?string the value of the attribute
	 */
	public function getAttribute( string $qname ): ?string {
		if ( $this->_attributes === null ) {
			return null;
		}
		$attr = $this->_attributes->getNamedItem( $qname );
		return $attr ? $attr->getValue() : null;
	}

	/**
	 * Set the value of first attribute with a particular qualifiedName
	 *
	 * spec DOM-LS
	 *
	 * NOTES
	 * Per spec, $value is not a string, but the string value of
	 * whatever is passed.
	 *
	 * TODO: DRY with this and setAttributeNS?
	 *
	 * @inheritDoc
	 */
	public function setAttribute( string $qname, string $value ) : void {
		if ( !WhatWG::is_valid_xml_name( $qname ) ) {
			Util::error( "InvalidCharacterError" );
		}

		if ( !ctype_lower( $qname ) && $this->_isHTMLElement() ) {
			$qname = Util::toAsciiLowercase( $qname );
		}

		$attributes = $this->getAttributes();
		$attr = $attributes->getNamedItem( $qname );
		if ( $attr === null ) {
			$attr = new Attr( $this, $qname, null, null, $value );
			$attributes->setNamedItem( $attr );
		} else {
			$attr->setValue( $value ); /* Triggers _handleAttributeChanges */
		}
	}

	/**
	 * Remove the first attribute given a particular qualifiedName
	 *
	 * spec DOM-LS
	 *
	 */
	public function removeAttribute( string $qname ): void {
		if ( $this->_attributes !== null ) {
			$attr = $this->_attributes->getNamedItem( $qname );
			if ( $attr !== null ) {
				// This throws an exception if the attribute is not found!
				$this->_attributes->removeNamedItem( $qname );
			}
		}
	}

	/**
	 * Test Element for attribute with the given qualified name
	 *
	 * spec DOM-LS
	 *
	 * @param string $qname Qualified name of attribute
	 * @return bool
	 */
	public function hasAttribute( string $qname ): bool {
		if ( $this->_attributes === null ) {
			return false;
		}
		return $this->_attributes->_hasNamedItem( $qname );
	}

	/**
	 * Toggle the first attribute with the given qualified name
	 *
	 * spec DOM-LS
	 *
	 * @param string $qname qualified name
	 * @param bool|null $force whether to set if no attribute exists
	 * @return bool whether we set or removed an attribute
	 */
	public function toggleAttribute( string $qname, ?bool $force = null ): bool {
		if ( !WhatWG::is_valid_xml_name( $qname ) ) {
			Util::error( "InvalidCharacterError" );
		}

		$a = $this->getAttributes()->getNamedItem( $qname );

		if ( $a === null ) {
			if ( $force === null || $force === true ) {
				$this->setAttribute( $qname, "" );
				return true;
			}
			return false;
		} else {
			if ( $force === null || $force === false ) {
				$this->removeAttribute( $qname );
				return false;
			}
			return true;
		}
	}

	/**********************************************************************
	 * ATTRIBUTE NS: get/set/remove/has
	 */

	/**
	 * Fetch value of attribute with the given namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * @param ?string $ns The attribute's namespace
	 * @param string $lname The attribute's local name
	 * @return ?string the value of the attribute
	 */
	public function getAttributeNS( ?string $ns, string $lname ): ?string {
		if ( $this->_attributes === null ) {
			return null;
		}
		$attr = $this->_attributes->getNamedItemNS( $ns, $lname );
		return $attr ? $attr->getValue() : null;
	}

	/**
	 * Set value of attribute with a particular namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * NOTES
	 * Per spec, $value is not a string, but the string value of
	 * whatever is passed.
	 *
	 * @inheritDoc
	 */
	public function setAttributeNS( ?string $ns, string $qname, string $value ) : void {
		$lname = null;
		$prefix = null;

		WhatWG::validate_and_extract( $ns, $qname, $prefix, $lname );

		$attributes = $this->getAttributes();
		$attr = $attributes->getNamedItemNS( $ns, $qname );
		if ( $attr === null ) {
			$attr = new Attr( $this, $lname, $prefix, $ns, $value );
			$attributes->setNamedItemNS( $attr );
		} else {
			$attr->setValue( $value );
		}
	}

	/**
	 * Remove attribute given a particular namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * @inheritDoc
	 */
	public function removeAttributeNS( ?string $ns, string $lname ) : void {
		if ( $this->_attributes !== null ) {
			$attr = $this->_attributes->getNamedItemNS( $ns, $lname );
			if ( $attr !== null ) {
				// This throws an exception if the attribute is not found!
				$this->_attributes->removeNamedItemNS( $ns, $lname );
			}
		}
	}

	/**
	 * Test Element for attribute with the given namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * @param ?string $ns the namespace
	 * @param string $lname the localName
	 * @return bool
	 */
	public function hasAttributeNS( ?string $ns, string $lname ): bool {
		if ( $this->_attributes === null ) {
			return false;
		}
		return $this->_attributes->_hasNamedItemNS( $ns, $lname );
	}

	/**********************************************************************
	 * ATTRIBUTE NODE: get/set/remove
	 */

	/**
	 * Fetch the Attr node with the given qualifiedName
	 *
	 * param string $lname The attribute's local name
	 * return ?Attr the attribute node, or NULL
	 * spec DOM-LS
	 */
	public function getAttributeNode( string $qname ): ?Attr {
		if ( $this->_attributes === null ) {
			return null;
		}
		return $this->_attributes->getNamedItem( $qname );
	}

	/**
	 * Add an Attr node to an Element node
	 *
	 * @inheritDoc
	 */
	public function setAttributeNode( $attr ) : ?Attr {
		return $this->getAttributes()->setNamedItem( $attr );
	}

	/**
	 * Remove the given attribute node from this Element
	 *
	 * spec DOM-LS
	 *
	 * @inheritDoc
	 */
	public function removeAttributeNode( $attr ) : Attr {
		$this->getAttributes()->_remove( $attr );
		return $attr;
	}

	/**********************************************************************
	 * ATTRIBUTE NODE NS: get/set
	 */

	/**
	 * Fetch the Attr node with the given namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * @param ?string $ns The attribute's local name
	 * @param string $lname The attribute's local name
	 * @return ?Attr the attribute node, or NULL
	 */
	public function getAttributeNodeNS( ?string $ns, string $lname ): ?Attr {
		if ( $this->_attributes === null ) {
			return null;
		}
		return $this->_attributes->getNamedItemNS( $ns, $lname );
	}

	/**
	 * Add a namespace-aware Attr node to an Element node
	 *
	 * @param Attr $attr
	 * @return ?Attr
	 */
	public function setAttributeNodeNS( $attr ) {
		return $this->getAttributes()->setNamedItemNS( $attr );
	}

	/*********************************************************************
	 * OTHER
	 */

	/**
	 * Test whether this Element has any attributes
	 *
	 * spec DOM-LS
	 *
	 * @return bool
	 */
	public function hasAttributes(): bool {
		if ( $this->_attributes === null ) {
			return false;
		}
		return count( $this->_attributes ) > 0;
	}

	/**
	 * Fetch the qualified names of all attributes on this Element
	 *
	 * spec DOM-LS
	 *
	 * NOTE
	 * The names are *not* guaranteed to be unique.
	 *
	 * @return array of strings, or empty array if no attributes.
	 */
	public function getAttributeNames(): array {
		/*
		 * Note that per spec, these are not guaranteed to be
		 * unique.
		 */
		$ret = [];

		foreach ( ( $this->_attributes ?? [] ) as $a ) {
			$ret[] = $a->getName();
		}

		return $ret;
	}

	/**
	 * @return mixed|null
	 */
	public function getClassList() {
		if ( $this->_classList === null ) {
			$this->_classList = new DOMTokenList( $this, 'class' );
		}
		return $this->_classList;
	}

	/**
	 * @param string $selectors
	 * @return bool
	 */
	public function matches( string $selectors ) : bool {
		return Zest::matches( $this, $selectors );
	}

	/**
	 * @param string $selectors
	 * @return ?Element
	 */
	public function closest( string $selectors ) {
		$el = $this;
		do {
			if ( $el instanceof Element && $el->matches( $selectors ) ) {
				return $el;
			}
			$el = $el->getParentElement() ?? $el->getParentNode();
		} while ( $el !== null && $el->getNodeType() == Node::ELEMENT_NODE );
		return null;
	}

	/*********************************************************************
	 * DODO EXTENSIONS
	 */

	/* Calls isHTMLDocument() on ownerDocument */
	public function _isHTMLElement() {
		if ( $this->getNamespaceURI() === Util::NAMESPACE_HTML
			 && $this->_ownerDocument
			 && $this->_ownerDocument->_isHTMLDocument() ) {
			return true;
		}
		return false;
	}

	/*
	 * Return the next element, in source order, after this one or
	 * null if there are no more.  If root element is specified,
	 * then don't traverse beyond its subtree.
	 *
	 * This is not a DOM method, but is convenient for
	 * lazy traversals of the tree.
	 * TODO: Change its name to __next_element then!
	 */
	public function _nextElement( $root ) {
		if ( !$root ) {
			$root = $this->getOwnerDocument()->getDocumentElement();
		}
		$next = $this->firstElementChild();
		if ( !$next ) {
			/* don't use sibling if we're at root */
			if ( $this === $root ) {
				return null;
			}
			$next = $this->getNextElementSibling();
		}
		if ( $next ) {
			return $next;
		}

		/*
		 * If we can't go down or across, then we have to go up
		 * and across to the parent sibling or another ancestor's
		 * sibling. Be careful, though: if we reach the root
		 * element, or if we reach the documentElement, then
		 * the traversal ends.
		 */
		for (
			$parent = $this->getParentElement();
			$parent && $parent !== $root;
			$parent = $parent->getParentElement()
		) {
			$next = $parent->getNextElementSibling();
			if ( $next ) {
				return $next;
			}
		}
		return null;
	}
}
