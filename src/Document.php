<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanParamSignatureMismatch
// @phan-file-suppress PhanTypeMismatchReturnNullable
// @phan-file-suppress PhanUndeclaredConstant
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.MethodDoubleUnderscore
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\MultiId;
use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\Util;
use Wikimedia\Dodo\Internal\WhatWG;

/**
 * The Document class.
 *
 * The HTML specification extends this class with a number of additional
 * methods for Documents which contain HTML.  We use "document type" to
 * distinguish between XML documents and HTML documents.
 *
 * Each document has an associated encoding (an encoding), content type
 * (a string), URL (a URL), origin (an origin), type ("xml" or "html"),
 * and mode ("no-quirks", "quirks", or "limited-quirks").
 *
 * Unless stated otherwise, a document’s encoding is the utf-8 encoding,
 * content type is "application/xml", URL is "about:blank", origin is an
 * opaque origin, type is "xml", and its mode is "no-quirks".
 *
 * A document is said to be an XML document if its type is "xml", and an
 * HTML document otherwise. Whether a document is an HTML document or an
 * XML document affects the behavior of certain APIs.
 *
 * A document is said to be in no-quirks mode if its mode is "no-quirks",
 * quirks mode if its mode is "quirks", and limited-quirks mode if its mode
 * is "limited-quirks".
 *
 * @see https://html.spec.whatwg.org/multipage/dom.html#document
 */
class Document extends Node implements \Wikimedia\IDLeDOM\Document {
	// DOM mixins
	use DocumentOrShadowRoot;
	use NonElementParentNode;
	use ParentNode;
	use XPathEvaluatorBase;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\Document;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Document;

	/**********************************************************************
	 * Properties that are for internal use by this library
	 */

	/**
	 * Encodings have a 'name' and one or more 'labels'.  This is the
	 * name of the document encoding.
	 * @var string Document encoding
	 * @see https://dom.spec.whatwg.org/#concept-document-encoding
	 */
	private $_encoding = 'UTF-8';

	/**
	 * Document type is "xml" or "html".  We use a boolean to represent
	 * this enumeration.
	 * @var bool True if document type is "html", else document type is "xml"
	 * @see https://dom.spec.whatwg.org/#concept-document-type
	 */
	private $_typeIsHtml = false;

	/**
	 * Document content type.
	 * @var string
	 * @see https://dom.spec.whatwg.org/#concept-document-content-type
	 */
	protected $_contentType = 'application/xml';

	/**
	 * Document URL.  This should probably be a more-complicated object
	 * type at some point, but we'll represent it internally as a string
	 * for now.
	 * @var string
	 * @see https://dom.spec.whatwg.org/#concept-document-url
	 */
	private $_URL = 'about:blank';

	/**
	 * Document Origin.  This should probably be a more-complicated tuple
	 * type at some point, but we'll represent it internally as a nullable
	 * string for now.
	 * @var ?string
	 * @see https://dom.spec.whatwg.org/#concept-document-origin
	 */
	private $_origin = null;

	/**
	 * Document mode: one of "no-quirks mode", "quirks mode", or
	 * "limited-quirks mode".  This is only ever changed from the default
	 * for documents created by the HTML parser.
	 * @var string
	 * @see https://dom.spec.whatwg.org/#concept-document-mode
	 */
	private $_mode = 'no-quirks';

	/*
	 * DEVELOPERS NOTE:
	 * Used to assign the document index to Nodes on ADOPTION.
	 */
	protected $__document_index_next = 2;

	/*
	 * DEVELOPERS NOTE:
	 * Document's aren't going to adopt themselves, so we set this to a default of 1.
	 */
	// XXX PORT FIXME this overrides a property of Node!
	//protected $__document_index = 1;

	/**
	 * Element nodes having an 'id' attribute are stored in this
	 * table, indexed by their 'id' value.
	 *
	 * This is how getElementById performs its fast lookup.
	 *
	 * The table must be mutated on:
	 *      - Element insertion
	 *      - Element removal
	 *      - mutation of 'id' attribute
	 *        on an inserted Element.
	 *
	 * @var array
	 */
	private $__id_to_element = [];

	/**********************************************************************
	 * Properties that appear in DOM-LS
	 */

	/*
	 * Part of Node parent class
	 */
	public $_ownerDocument = null;

	/*
	 * ANNOYING LIVE REFERENCES
	 *
	 * The below are slightly annoying because we must keep them updated
	 * whenever there is mutation to the children of the Document.
	 */

	/*
	 * Reference to the first DocumentType child, in document order.
	 * Null if no such child exists.
	 */
	public $_doctype = null;

	/*
	 * Reference to the first Element child, in document order.
	 * Null if no such child exists.
	 */
	public $_documentElement = null;

	/**
	 * Called when a child is inserted or removed from the document.
	 * Keeps the above references live.
	 */
	private function __rereference_doctype_and_documentElement(): void {
		$this->_doctype = null;
		$this->_documentElement = null;

		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
				$this->_doctype = $n;
			} elseif ( $n->getNodeType() === Node::ELEMENT_NODE ) {
				$this->_documentElement = $n;
			}
		}
	}

	/* TODO: These three amigos. */
	public $_implementation;
	public $_readyState;
	public $__mutation_handler = null;

	// USED EXCLUSIVELY IN htmlelts.js to make <TEMPLATE>
	private $_templateDocCache;

	/**
	 * @param ?Document $originDoc
	 * @param string $type
	 * @param ?string $url
	 */
	public function __construct(
		?Document $originDoc = null,
		string $type = "xml",
		?string $url = null
	) {
		parent::__construct();

		/** DOM-LS */
		$this->_origin = $originDoc ? $originDoc->_origin : null; // default

		/* Having an HTML Document affects some APIs */
		if ( $type === 'html' ) {
			$this->_contentType = 'text/html';
			$this->_typeIsHtml = true;
		}

		/* DOM-LS: used by the documentURI and URL method */
		if ( $url !== null ) {
			$this->_URL = $url;
		}

		/* DOM-LS: DOMImplementation associated with document */
		$this->_implementation = new DOMImplementation( $this );

		/** JUNK */

		$this->_readyState = "loading";

		/* USED EXCLUSIVELY IN htmlelts.js to make <TEMPLATE> */
		$this->_templateDocCache = null;
	}

	/* USED EXCLUSIVELY IN htmlelts.js to make <TEMPLATE> */
	public function _templateDoc() {
		if ( !$this->_templateDocCache ) {
			/* "associated inert template document" */
			$newDoc = new Document(
				$this,
				$this->_typeIsHtml ? 'html' : 'xml',
				$this->_URL
			);
			$this->_templateDocCache = $newDoc->_templateDocCache = $newDoc;
		}
		return $this->_templateDocCache;
	}

	/*
	 * Accessors for read-only properties defined in Document
	 */

	/**
	 * @copydoc Node::getNodeType()
	 * @inheritDoc
	 */
	public function getNodeType() : int {
		return Node::DOCUMENT_NODE;
	}

	/**
	 * @copydoc Node::getNodeName()
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return "#document";
	}

	/**
	 * @copydoc Wikimedia\IDLeDOM\Document::getCharacterSet()
	 * @inheritDoc
	 */
	public function getCharacterSet(): string {
		return $this->_encoding;
	}

	/** @return string */
	public function getCharset(): string {
		return $this->getCharacterSet(); /* historical alias */
	}

	/** @return string */
	public function getInputEncoding(): string {
		return $this->getCharacterSet(); /* historical alias */
	}

	/** @return DOMImplementation */
	public function getImplementation(): DOMImplementation {
		return $this->_implementation;
	}

	/** @inheritDoc */
	public function getDocumentURI() : string {
		return $this->_URL;
	}

	/** @return string */
	public function getURL() : string {
		return $this->getDocumentURI(); /** Alias for HTMLDocuments */
	}

	/** @inheritDoc */
	public function getCompatMode() : string {
		return $this->_mode === "quirks" ? "BackCompat" : "CSS1Compat";
	}

	/** @inheritDoc */
	public function getContentType(): string {
		return $this->_contentType;
	}

	/** @inheritDoc */
	public function getDoctype() {
		return $this->_doctype;
	}

	/** @inheritDoc */
	public function getDocumentElement() {
		return $this->_documentElement;
	}

	/*
	 * NODE CREATION
	 */

	/** @inheritDoc */
	public function createTextNode( string $data ) {
		return new Text( $this, $data );
	}

	/** @inheritDoc */
	public function createComment( string $data ) {
		return new Comment( $this, $data );
	}

	/** @inheritDoc */
	public function createDocumentFragment() {
		return new DocumentFragment( $this );
	}

	/** @inheritDoc */
	public function createProcessingInstruction( string $target, string $data ) {
		if ( !WhatWG::is_valid_xml_name( $target ) || strpos( $data, '?' . '>' ) !== false ) {
			Util::error( 'InvalidCharacterError' );
		}
		return new ProcessingInstruction( $this, $target, $data );
	}

	/** @inheritDoc */
	public function createAttribute( string $localName ) {
		if ( !WhatWG::is_valid_xml_name( $localName ) ) {
			Util::error( 'InvalidCharacterError' );
		}
		if ( $this->_isHTMLDocument() ) {
			$localName = Util::ascii_to_lowercase( $localName );
		}
		return new Attr( null, $localName, null, null, '' );
	}

	/** @inheritDoc */
	public function createAttributeNS( ?string $ns, string $qname ) {
		if ( $ns === '' ) {
			$ns = null; /* spec */
		}

		$lname = null;
		$prefix = null;

		WhatWG::validate_and_extract( $ns, $qname, $prefix, $lname );

		return new Attr( null, $lname, $prefix, $ns, '' );
	}

	/** @inheritDoc */
	public function createElement( string $lname, $options = null ) {
		if ( !WhatWG::is_valid_xml_name( $lname ) ) {
			Util::error( "InvalidCharacterError" );
		}

		/*
		 * Per spec, namespace should be HTML namespace if
		 * "context object is an HTML document or context
		 * object's content type is "application/xhtml+xml",
		 * and null otherwise.
		 */
		return new Element( $this, $lname, null, null );

		// if ($this->_contentType === 'text/html') {
		//if (!ctype_lower($lname)) {
		//$lname = Util::ascii_to_lowercase($lname);
		//}

		//[> TODO STUB <]
		////return Dodo\html\createElement($this, $lname, NULL);

		//} else if ($this->_contentType === 'application/xhtml+xml') {
		//[> TODO STUB <]
		////return Dodo\html\createElement($this, $lname, NULL);
		//} else {
		//return new Element($this, $lname, NULL, NULL);
		//}
	}

	/** @inheritDoc */
	public function createElementNS( ?string $ns, string $qname, $options = null ) {
		/* Convert parameter types according to WebIDL */
		if ( $ns === null || $ns === "" ) {
			$ns = null;
		} else {
			$ns = strval( $ns );
		}

		$qname = strval( $qname );

		$lname = null;
		$prefix = null;

		WhatWG::validate_and_extract( $ns, $qname, $prefix, $lname );

		return $this->_createElementNS( $lname, $ns, $prefix );
	}

	/*
	 * This is used directly by HTML parser, which allows it to create
	 * elements with localNames containing ':' and non-default namespaces
	 */
	public function _createElementNS( $lname, $ns, $prefix ) {
		if ( $ns === Util::NAMESPACE_HTML ) {
			/* TODO STUB */
			//return Dodo\html\createElement($this, $lname, $prefix);
		} elseif ( $ns === Util::NAMESPACE_SVG ) {
			/* TODO STUB */
			//return svg\createElement($this, $lname, $prefix);
		} else {
			return new Element( $this, $lname, $ns, $prefix );
		}
	}

	/*********************************************************************
	 * MUTATION
	 */

	/**
	 * Adopt the subtree rooted at Node into this Document.
	 *
	 * This means setting ownerDocument of each node in the subtree to point to $this.
	 *
	 * No insertion is performed, but if Node is inserted into another Document,
	 * it will be removed.
	 *
	 * @inheritDoc
	 */
	public function adoptNode( $node ) {
		if ( $node->getNodeType() === Node::DOCUMENT_NODE ) {
			// A Document cannot adopt another Document. Throw a "NotSupported" exception.
			Util::error( "NotSupported" );
		}
		if ( $node->getNodeType() === Node::ATTRIBUTE_NODE ) {
			// Attributes do not have an ownerDocument, so do nothing.
			return $node;
		}
		if ( $node->getParentNode() ) {
			/*
			 * If the Node is currently inserted in some Document, remove it.
			 *
			 * TODO:
			 * Why is this not using $node->__is_rooted()?
			 * Is this diagnostic for rooted-ness? Why
			 * doesn't __is_rooted() just do this?
			 */
			$node->getParentNode()->removeChild( $node );
		}
		if ( $node->_ownerDocument !== $this ) {
			/*
			 * If the Node is not currently connected to this Document,
			 * then recursively set the ownerDocument.
			 *
			 * (The recursion skips the above checks because they don't make sense.)
			 */
			$node->__set_owner( $this );
		}

		/* DOM-LS requires this return $node */
		return $node;
	}

	/**
	 * Clone and then adopt either $node or, if $deep === true, the entire subtree
	 * rooted at $node, into the Document.
	 *
	 * By default, only $node will be cloned.
	 *
	 * @inheritDoc
	 */
	public function importNode( $node, bool $deep = false ) {
		return $this->adoptNode( $node->cloneNode( $deep ) );
	}

	/*
	 * The following three methods are a simple extension of the Node methods, with an
	 * added call to update the doctype and documentElement references that are specific
	 * to the Document interface.
	 *
	 * Note: appendChild is not extended, because it calls insertBefore.
	 */

	/**
	 * @inheritDoc
	 */
	public function insertBefore( $node, $refChild ) {
		$ret = parent::insertBefore( $node, $refChild );
		$this->__rereference_doctype_and_documentElement();
		return $ret;
	}

	/**
	 * @inheritDoc
	 */
	public function replaceChild( $node, $child ) {
		$ret = parent::replaceChild( $node, $child );
		$this->__rereference_doctype_and_documentElement();
		return $ret;
	}

	/**
	 * @inheritDoc
	 */
	public function removeChild( $child ) {
		$ret = parent::removeChild( $child );
		$this->__rereference_doctype_and_documentElement();
		return $ret;
	}

	/**
	 * Clone this Document, import nodes, and call __update_document_state
	 *
	 * extends Node::cloneNode()
	 * spec DOM-LS
	 *
	 * NOTE:
	 * 1. What a tangled web we weave
	 * 2. With Document nodes, we need to take the additional step of
	 *    calling importNode() to bring copies of child nodes into this
	 *    document.
	 * 3. We also need to call _updateDocTypeElement()
	 *
	 * @inheritDoc
	 */
	public function cloneNode( bool $deep = false ) {
		/* Make a shallow clone  */
		$clone = parent::cloneNode( false );

		if ( $deep === false ) {
			/* Return shallow clone */
			$clone->__rereference_doctype_and_documentElement();
			return $clone;
		}

		/* Clone children too */
		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			$clone->appendChild( $clone->importNode( $n, true ) );
		}

		$clone->__rereference_doctype_and_documentElement();
		return $clone;
	}

	/*
	 * Query methods
	 */

	/**
	 * Fetch an Element in this Document with a given ID value
	 *
	 * spec DOM-LS
	 *
	 * NOTE
	 * In the spec, this is actually the sole method of the
	 * NonElementParentNode mixin.
	 *
	 * @inheritDoc
	 */
	public function getElementById( string $id ) {
		$n = $this->__id_to_element[$id] ?? null;
		if ( $n === null ) {
			return null;
		}
		if ( $n instanceof MultiId ) {
			/* there was more than one element with this id */
			return $n->get_first();
		}
		return $n;
	}

	/*
	 * Utility methods extending normal DOM behavior
	 */

	/**
	 * Return true if this document is an HTML document, otherwise it
	 * is an XML document and will return false.
	 * @see https://dom.spec.whatwg.org/#html-document
	 *
	 * @return bool
	 */
	public function _isHTMLDocument(): bool {
		return $this->_typeIsHtml;
	}

	/**
	 * Delegated method called by Node::cloneNode()
	 * Performs the shallow clone branch.
	 *
	 * spec Dodo
	 *
	 * @return Document with same invocation as $this
	 */
	protected function _subclass_cloneNodeShallow(): Node {
		$shallow = new Document(
			$this,
			$this->_typeIsHtml ? 'html' : 'xml',
			$this->_URL
		);
		$shallow->_mode = $this->_mode;
		$shallow->_contentType = $this->_contentType;
		return $shallow;
	}

	/**
	 * Delegated method called by Node::isEqualNode()
	 *
	 * spec DOM-LS
	 *
	 * NOTE:
	 * Any two Documents are shallowly equal, since equality
	 * is determined by their children; this will be tested by
	 * Node::isEqualNode(), so just return true.
	 *
	 * @param Node|null $other to compare
	 * @return bool True (two Documents are always equal)
	 */
	protected function _subclass_isEqualNode( Node $other = null ): bool {
		return true;
	}

	/*
	 * Internal book-keeping tables:
	 *
	 * Documents manage 2: the node table, and the id table.
	 * <full explanation goes here>
	 *
	 * Called by Node::__root() and Node::__uproot()
	 *
	 * See, we are adding, and removing, but never using...?
	 */

	/**
	 * @param string $id
	 * @param Element $elt
	 */
	public function __add_to_id_table( string $id, Element $elt ): void {
		if ( !isset( $this->__id_to_element[$id] ) ) {
			$this->__id_to_element[$id] = $elt;
		} else {
			if ( !( $this->__id_to_element[$id] instanceof MultiId ) ) {
				$this->__id_to_element[$id] = new MultiId(
					$this->__id_to_element[$id]
				);
			}
			$this->__id_to_element[$id]->add( $elt );
		}
	}

	/**
	 * @param string $id
	 * @param Element $elt
	 */
	public function __remove_from_id_table( string $id, Element $elt ): void {
		if ( isset( $this->__id_to_element[$id] ) ) {
			if ( $this->__id_to_element[$id] instanceof MultiId ) {
				$item = $this->__id_to_element[$id];
				$item->del( $elt );

				// convert back to a single node
				if ( $item->length === 1 ) {
					$this->__id_to_element[$id] = $item->downgrade();
				}
			} else {
				unset( $this->__id_to_element[$id] );
			}
		}
	}

	/*
	 * MUTATION STUFF
	 * TODO: The mutationHandler checking
	 *
	 * NOTES:
	 * Whenever a document is updated, these mutation functions
	 * are called, e.g. Node::_insertOrReplace.
	 *
	 * To attach a handler to watch how a document is mutated,
	 * you set the handler in DOMImplementation. It will be
	 * provided with a single argument, an array.
	 *
	 * See usage below.
	 *
	 * These mutations have nothing to do with MutationEvents or
	 * MutationObserver, which is confusing.
	 */

	/*
	 * Implementation-specific function.  Called when a text, comment,
	 * or pi value changes.
	 */
	public function __mutate_value( $node ) {
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_VALUE,
				"target" => $node,
				"data" => $node->getData()
			] );
		}
	}

	/*
	 * Invoked when an attribute's value changes. Attr holds the new
	 * value.  oldval is the old value.  Attribute mutations can also
	 * involve changes to the prefix (and therefore the qualified name)
	 */
	public function __mutate_attr( $attr, $oldval ) {
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_ATTR,
				"target" => $attr->getOwnerElement(),
				"attr" => $attr
			] );
		}
	}

	/* Used by removeAttribute and removeAttributeNS for attributes. */
	public function __mutate_remove_attr( $attr ) {
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_REMOVE_ATTR,
				"target" => $attr->getOwnerElement(),
				"attr" => $attr
			] );
		}
	}

	/*
	 * Called by Node.removeChild, etc. to remove a rooted element from
	 * the tree. Only needs to generate a single mutation event when a
	 * node is removed, but must recursively mark all descendants as not
	 * rooted.
	 */
	public function __mutate_remove( $node ) {
		/* Send a single mutation event */
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_REMOVE,
				"target" => $node->getParentNode(),
				"node" => $node
			] );
		}
	}

	/*
	 * Called when a new element becomes rooted.  It must recursively
	 * generate mutation events for each of the children, and mark
	 * them all as rooted.
	 *
	 * Called in Node::_insertOrReplace.
	 */
	public function __mutate_insert( $node ) {
		/* Send a single mutation event */
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_INSERT,
				"target" => $node->getParentNode(),
				"node" => $node
			] );
		}
	}

	/*
	 * Called when a rooted element is moved within the document
	 */
	public function __mutate_move( $node ) {
		if ( $this->__mutation_handler ) {
			$this->__mutation_handler( [
				"type" => MUTATE_MOVE,
				"target" => $node
			] );
		}
	}
}
