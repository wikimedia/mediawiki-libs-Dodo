<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/******************************************************************************
 * Comment.php
 * -----------
 */
class Comment extends CharacterData implements \Wikimedia\IDLeDOM\Comment {
	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Comment {
		__get as protected _getHelper;
	}

	/**
	 * HACK! For compatibilty with W3C test suite, which assumes that an
	 * access to 'attributes' will return null.
	 * @param string $name
	 * @return mixed
	 */
	public function __get( string $name ) {
		if ( $name === 'attributes' ) {
			return null;
		}
		return $this->_getHelper( $name );
	}

	/**
	 * Create a new Comment node.
	 * @param Document $nodeDocument
	 * @param string $data
	 */
	public function __construct( Document $nodeDocument, $data ) {
		parent::__construct( $nodeDocument );
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

	/** @return Comment */
	protected function _subclassCloneNodeShallow(): Node {
		return new Comment( $this->_nodeDocument, $this->_data );
	}

	/** @inheritDoc */
	protected function _subclassIsEqualNode( Node $node ): bool {
		'@phan-var Comment $node'; /** @var Comment $node */
		return ( $this->_data === $node->_data );
	}

	/** @inheritDoc */
	public function clone(): Comment {
		/*
		* TODO: Does this override directly?
		* Or should we use _subclass_clone_shallow?
		*/
		return new Comment( $this->_nodeDocument, $this->_data );
	}
}
