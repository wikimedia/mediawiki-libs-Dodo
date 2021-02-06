<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanParamTooFew
// @phan-file-suppress PhanTypeMismatchArgument
// @phan-file-suppress PhanTypeMismatchArgumentReal
// @phan-file-suppress PhanTypeMismatchDeclaredParamNullable
// @phan-file-suppress PhanTypeMismatchDeclaredReturnNullable
// @phan-file-suppress PhanTypeMismatchReturnNullable
// @phan-file-suppress PhanUndeclaredClassMethod
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanUndeclaredVariable
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingReturn
// phpcs:disable MediaWiki.Commenting.FunctionComment.SpacingAfter
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

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

/*
 * OPTIMIZATION: When we create a DOM tree, we will likely create many
 * elements with the same tag name / qualified name, and thus need to
 * repeatedly convert those strings to ASCII uppercase to form the
 * HTML-uppercased qualified name.
 *
 * This table caches the results of that ASCII uppercase conversion,
 * turning subsequent calls into O(1) table lookups.
 */
$UC_Cache = [];

class Element extends NonDocumentTypeChildNode {
	/* Required by Node */
	public $_nodeType = Node::ELEMENT_NODE;

	/* Provided by Node
	   public $_nodeValue = NULL;
	   public $_nodeName = NULL; // HTML-uppercased qualified name
	   public $_ownerDocument = NULL;
	*/

	/* Required by Element */
	public $_namespaceURI = null;
	public $_localName = null;
	public $_prefix = null;

	/* Actually attached without an accessor */
	public $attributes = null;

	/* Watch these attributes */
	public $__onchange_attr = [];

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
		global $UC_Cache; /* See declaration, above */

		parent::__construct();

		$this->__onchange_attr = [
			"id" => function ( $elem, $old, $new ) {
				if ( !$elem->__is_rooted() ) {
					return;
				}
				if ( $old ) {
					$elem->_ownerDocument->__remove_from_id_table( $old, $elem );
				}
				if ( $new ) {
					$elem->_ownerDocument->__add_to_id_table( $new, $elem );
				}
			},
			"class" => function ( $elem, $old, $new ) {
				if ( $elem->_classList ) {
					$elem->_classList->_update();
				}
			}
		];

		/*
		 * TODO
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
		 * DOM-LS: "An Element's qualified name is its local name
		 * if its namespace prefix is null, and its namespace prefix,
		 * followed by ":", followed by its local name, otherwise."
		 */
		$qname = ( $prefix === null ) ? $lname : "$prefix:$lname";

		/*
		 * DOM-LS: "An element's tagName is its HTML-uppercased
		 * qualified name".
		 *
		 * DOM-LS: "If an Element is in the HTML namespace and its node
		 * document is an HTML document, then its HTML-uppercased
		 * qualified name is its qualified name in ASCII uppercase.
		 * Otherwise, its HTML-uppercased qualified name is its
		 * qualified name."
		 */
		if ( $this->isHTMLElement() ) {
			if ( !isset( $UC_Cache[$qname] ) ) {
				$uc_qname = Util::ascii_to_uppercase( $qname );
				$UC_Cache[$qname] = $uc_qname;
			} else {
				$uc_qname = $UC_Cache[$qname];
			}
		} else {
			/* If not an HTML element, don't uppercase. */
			$uc_qname = $qname;
		}

		/*
		 * DOM-LS: "User agents could optimize qualified name and
		 * HTML-uppercased qualified name by storing them in internal
		 * slots."
		 */
		$this->_nodeName = $uc_qname;

		/*
		 * DOM-LS: "Elements also have an attribute list, which is
		 * a list exposed through a NamedNodeMap. Unless explicitly
		 * given when an element is created, its attribute list is
		 * empty."
		 */
		$this->attributes = new NamedNodeMap( $this );
	}

	/**********************************************************************
	 * ACCESSORS
	 */

	/* TODO: Also in Attr... are they part of Node ? */
	public function prefix(): ?string {
		return $this->_prefix;
	}

	/* TODO: Also in Attr... are they part of Node ? */
	public function localName(): string {
		return $this->_localName;
	}

	/* TODO: Also in Attr... are they part of Node ? */
	public function namespaceURI(): ?string {
		return $this->_namespaceURI;
	}

	public function tagName(): string {
		return $this->_nodeName;
	}

	public function id( ?string $v = null ) {
		if ( $v === null ) {
			return $this->getAttribute( "id" );
		} else {
			return $this->setAttribute( "id", $v );
		}
	}

	/**
	 * Get or set the text content of an Element.
	 *
	 * Spec: DOM-LS
	 * Implements: abstract public Node::textContent
	 *
	 * @param string|null $value
	 * @return ?string
	 */
	public function textContent( ?string $value = null ) {
		/* GET */
		if ( $value === null ) {
			$text = [];
			Algorithm::descendant_text_content( $this, $text );
			return implode( "", $text );
			/* SET */
		} else {
			$this->__remove_children();
			if ( $value !== "" ) {
				/* Equivalent to Node:: appendChild without checks! */
				WhatWG::insert_before_or_replace( $node, $this->_ownerDocument->createTextNode( $value ), null );
			}
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
		if ( $this->namespaceURI() !== Util::NAMESPACE_HTML
			 || $this->prefix()
			 || !$this->ownerDocument()->isHTMLDocument() ) {
			if ( $this->prefix() === null ) {
				$name = $this->localName();
			} else {
				$name = $this->prefix() . ':' . $this->localName();
			}
			$clone = $this->ownerDocument()->createElementNS(
				$this->namespaceURI(),
				$name
			);
		} else {
			$clone = $this->ownerDocument()->createElement(
				$this->localName()
			);
		}

		foreach ( $this->attributes as $a ) {
			$clone->setAttributeNodeNS( $a->cloneNode() );
		}

		return $clone;
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		if ( $this->localName() !== $node->localName()
			 || $this->namespaceURI() !== $node->namespaceURI()
			 || $this->prefix() !== $node->prefix()
			 || count( $this->attributes ) !== count( $node->attributes ) ) {
			return false;
		}

		/*
		 * Compare the sets of attributes, ignoring order
		 * and ignoring attribute prefixes.
		 */
		foreach ( $this->attributes as $a ) {
			if ( !$node->hasAttributeNS( $a->namespaceURI(), $a->localName() ) ) {
				return false;
			}
			if ( $node->getAttributeNS( $a->namespaceURI(), $a->localName() ) !== $a->value() ) {
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
		$attr = $this->attributes->getNamedItem( $qname );
		return $attr ? $attr->value() : null;
	}

	/**
	 * Set the value of first attribute with a particular qualifiedName
	 *
	 * param string $qname
	 * param $value
	 * return void
	 * spec DOM-LS
	 *
	 * NOTES
	 * Per spec, $value is not a string, but the string value of
	 * whatever is passed.
	 *
	 * TODO: DRY with this and setAttributeNS?
	 */
	public function setAttribute( string $qname, $value ) {
		if ( !WhatWG::is_valid_xml_name( $qname ) ) {
			Util::error( "InvalidCharacterError" );
		}

		if ( !ctype_lower( $qname ) && $this->isHTMLElement() ) {
			$qname = Util::ascii_to_lowercase( $qname );
		}

		$attr = $this->attributes->getNamedItem( $qname );
		if ( $attr === null ) {
			$attr = new Attr( $this, $qname, null, null );
		}
		$attr->value( $value ); /* Triggers __onchange_attr */
		$this->attributes->setNamedItem( $attr );
	}

	/**
	 * Remove the first attribute given a particular qualifiedName
	 *
	 * spec DOM-LS
	 *
	 * @param string $qname
	 * @return Attr or NULL the removed attribute node
	 */
	public function removeAttribute( string $qname ): ?Attr {
		return $this->attributes->removeNamedItem( $qname );
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
		return $this->attributes->hasNamedItem( $qname );
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

		$a = $this->attributes->getNamedItem( $qname );

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
		$attr = $this->attributes->getNamedItemNS( $ns, $lname );
		return $attr ? $attr->value() : null;
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
	 * @param string $ns
	 * @param string $qname
	 * @param mixed $value
	 * @return void
	 */
	public function setAttributeNS( ?string $ns, string $qname, $value ) {
		$lname = null;
		$prefix = null;

		WhatWG::validate_and_extract( $ns, $qname, $prefix, $lname );

		$attr = $this->attributes->getNamedItemNS( $ns, $qname );
		if ( $attr === null ) {
			$attr = new Attr( $this, $lname, $prefix, $ns );
		}
		$attr->value( $value );
		$this->attributes->setNamedItemNS( $attr );
	}

	/**
	 * Remove attribute given a particular namespace and localName
	 *
	 * spec DOM-LS
	 *
	 * @param string $ns namespace
	 * @param string $lname localName
	 * @return Attr or NULL the removed attribute node
	 */
	public function removeAttributeNS( ?string $ns, string $lname ) {
		return $this->attributes->removeNamedItemNS( $ns, $lname );
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
		return $this->attributes->hasNamedItemNS( $ns, $lname );
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
		return $this->attributes->getNamedItem( $qname );
	}

	/**
	 * Add an Attr node to an Element node
	 *
	 * @param Attr $attr
	 * @return ?Attr
	 */
	public function setAttributeNode( Attr $attr ): ?Attr {
		return $this->attributes->setNamedItem( $attr );
	}

	/**
	 * Remove the given attribute node from this Element
	 *
	 * spec DOM-LS
	 *
	 * @param Attr $attr attribute node to remove
	 * @return Attr or NULL the removed attribute node
	 */
	public function removeAttributeNode( Attr $attr ) {
		/* TODO: This is not a public function */
		return $this->attributes->_remove( $attr );
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
		return $this->attributes->getNamedItemNS( $ns, $lname );
	}

	/**
	 * Add a namespace-aware Attr node to an Element node
	 *
	 * @param Attr $attr
	 * @return ?Attr
	 */
	public function setAttributeNodeNS( $attr ) {
		return $this->attributes->setNamedItemNS( $attr );
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
		return !empty( $this->attributes->index_to_attr );
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

		foreach ( $this->attributes as $a ) {
			$ret[] = $a->name();
		}

		return $ret;
	}

	public function classList() {
		$self = $this;
		if ( $this->_classList ) {
			return $this->_classList;
		}
		/* TODO: FIx this into PHP
		   $dtlist = new DOMTokenList(
		   function() {
		   return self.className || "";
		   },
		   function(v) {
		   self.className = v;
		   }
		   );
		*/
		$this->_classList = $dtlist;
		return $dtlist;
	}

	/*********************************************************************
	 * DODO EXTENSIONS
	 */

	/* Calls isHTMLDocument() on ownerDocument */
	public function isHTMLElement() {
		if ( $this->_namespaceURI === Util::NAMESPACE_HTML
			 && $this->_ownerDocument
			 && $this->_ownerDocument->isHTMLDocument() ) {
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
	public function nextElement( $root ) {
		if ( !$root ) {
			$root = $this->ownerDocument()->documentElement();
		}
		$next = $this->firstElementChild();
		if ( !$next ) {
			/* don't use sibling if we're at root */
			if ( $this === $root ) {
				return null;
			}
			$next = $this->nextElementSibling();
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
		for ( $parent = $this->parentElement(); $parent && $parent !== $root; $parent = $parent->parentElement() ) {
			$next = $parent->nextElementSibling();
			if ( $next ) {
				return $next;
			}
		}
		return null;
	}
}
