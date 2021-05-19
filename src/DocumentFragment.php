<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanParamTooFew
// @phan-file-suppress PhanTypeMismatchArgumentReal
// @phan-file-suppress PhanTypeMissingReturn
// @phan-file-suppress PhanUndeclaredClassMethod
// @phan-file-suppress PhanUndeclaredVariable
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\WhatWG;

/**
 * DocumentFragment
 */
class DocumentFragment extends ContainerNode implements \Wikimedia\IDLeDOM\DocumentFragment {
	// DOM mixins
	use NonElementParentNode;
	use ParentNode;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\DocumentFragment;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\DocumentFragment;

	public function __construct( Document $nodeDocument ) {
		parent::__construct( $nodeDocument );
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::DOCUMENT_FRAGMENT_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return "#document-fragment";
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
			WhatWG::insert_before_or_replace( $node, $this->_nodeDocument->createTextNode( $value ), null );
		}
	}

	public function querySelector( $selector ) {
		// implement in terms of querySelectorAll
		/* TODO stub */
		$nodes = $this->querySelectorAll( $selector );
		return count( $nodes ) ? $nodes[0] : null;
	}

	public function querySelectorAll( $selector ) {
		/* TODO: Stub */
		//// create a context
		//var context = Object.create(this);
		//// add some methods to the context for zest implementation, without
		//// adding them to the public DocumentFragment API
		//context.isHTML = true; // in HTML namespace (case-insensitive match)
		//context.getElementsByTagName = Element.prototype.getElementsByTagName;
		//context.nextElement =
		//Object.getOwnPropertyDescriptor(Element.prototype, 'firstElementChild').get;
		//// invoke zest
		//var nodes = select(selector, context);
		//return nodes.item ? nodes : new NodeList(nodes);
	}

	public function getElementsByTagName( $tag ) {
		/* TODO: Stub */
	}

	/* TODO DELEGATED FROM NODE */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new DocumentFragment( $this->_nodeDocument );
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		// Any two document fragments are shallowly equal.
		// Node.isEqualNode() will test their children for equality
		return true;
	}

	// Non-standard, but useful (github issue #73)
	public function innerHTML() {
		return $this->_node_serialize();
	}

	public function outerHTML( ?string $value = null ) {
		return $this->_node_serialize();
	}
}
