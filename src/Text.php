<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanCoalescingNeverNull
// @phan-file-suppress PhanCoalescingNeverUndefined
// @phan-file-suppress PhanUndeclaredMethod
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic

namespace Wikimedia\Dodo;

/******************************************************************************
 * Text.php
 * --------
 */
class Text extends CharacterData implements \Wikimedia\IDLeDOM\Text {
	// DOM mixins
	use Slottable;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\Text;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Text;

	/**
	 * @param Document $doc
	 * @param mixed $data
	 */
	public function __construct( Document $doc, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_data = $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getNodeType() : int {
		return Node::TEXT_NODE;
	}

	/**
	 * Overrides Node::nodeValue
	 *
	 * @inheritDoc
	 */
	public function getNodeValue() : ?string {
			return $this->_data;
	}

	/**
	 * @inheritDoc
	 */
	public function getNodeName() : string {
		return "#text";
	}

	/** @inheritDoc */
	public function setNodeValue( ?string $value ) : void {
		if ( $value === $this->_data ) {
			return;
		}

		$this->_data = $value;

		if ( $this->__is_rooted() ) {
			$this->_ownerDocument->__mutate_value( $this );
		}

		if ( $this->_parentNode && $this->_parentNode->_textchangehook ?? null ) {
			$this->_parentNode->_textchangehook( $this );
		}
	}

	/**
	 * @param Node $node
	 * @return bool
	 */
	public function _subclass_isEqualNode( Node $node ): bool {
		return ( $this->_data === $node->_data );
	}

	/**
	 * @return ?Node always Text
	 */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new Text( $this->_ownerDocument, $this->_data );
	}

	/**
	 * Per spec
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function textContent( $value = null ) {
		return $this->nodeValue( $value );
	}

	/** @inheritDoc */
	public function getData() : string {
		return $this->getNodeValue() ?? '';
	}

	/** @inheritDoc */
	public function setData( string $val ) : void {
		$this->setNodeValue( $val );
	}

	/**
	 * @param int $offset
	 * @return Text
	 */
	public function splitText( $offset ) {
		if ( $offset > strlen( $this->_data ) || $offset < 0 ) {
			Util::error( "IndexSizeError" );
		}

		$newdata = substr( $this->_data, $offset );
		$newnode = $this->_ownerDocument->createTextNode( $newdata );
		$this->nodeValue( substr( $this->_data, 0, $offset ) );

		$parent = $this->parentNode();

		if ( $parent !== null ) {
			$parent->insertBefore( $newnode, $this->nextSibling() );
		}
		return $newnode;
	}

	/**
	 * @return string
	 */
	public function wholeText() {
		$result = $this->textContent();

		for ( $n = $this->nextSibling(); $n !== null; $n = $n->nextSibling() ) {
			if ( $n->getNodeType() !== Node::TEXT_NODE ) {
				break;
			}
			$result .= $n->textContent();
		}
		return $result;
	}
}
