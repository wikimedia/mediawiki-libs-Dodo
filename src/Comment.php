<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanImpossibleTypeComparison
// @phan-file-suppress PhanRedundantCondition
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic

namespace Wikimedia\Dodo;

/******************************************************************************
 * Comment.php
 * -----------
 */
class Comment extends CharacterData implements \Wikimedia\IDLeDOM\Comment {
	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Comment;

	public function __construct( Document $doc, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_data = $data;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::COMMENT_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return "#comment";
	}

	public function _subclass_cloneNodeShallow(): ?Node {
		return new Comment( $this->_ownerDocument, $this->_data );
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		'@phan-var Comment $node'; /** @var Comment $node */
		return ( $this->_data === $node->_data );
	}

	public function nodeValue( ?string $value = null ) {
		if ( $value === null ) {
			return $this->_data;
		} else {
			$value = ( $value === null ) ? '' : strval( $value );

			$this->_data = $value;

			if ( $this->__is_rooted() ) {
				$this->_ownerDocument->__mutate_value( $this );
			}
		}
	}

	public function textContent( ?string $value = null ) {
		return $this->nodeValue( $value );
	}

	public function getData() : string {
		return $this->getNodeValue() ?? '';
	}

	public function setData( string $val ) : void {
		$this->setNodeValue( $val );
	}

	/** @inheritDoc */
	public function clone(): Comment {
		/*
		* TODO: Does this override directly?
		* Or should we use _subclass_clone_shallow?
		*/
		return new Comment( $this->_ownerDocument, $this->_data );
	}
}
