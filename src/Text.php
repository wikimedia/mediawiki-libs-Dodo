<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\Util;

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
	 * @param Document $nodeDocument
	 * @param string $data
	 */
	public function __construct( Document $nodeDocument, string $data ) {
		parent::__construct( $nodeDocument );
		$this->_data = $data;
	}

	/**
	 * @inheritDoc
	 */
	public function getNodeType() : int {
		return Node::TEXT_NODE;
	}

	/**
	 * @inheritDoc
	 */
	public function getNodeName() : string {
		return "#text";
	}

	/**
	 * @param Node $node
	 * @return bool
	 */
	protected function _subclassIsEqualNode( Node $node ): bool {
		return ( $this->_data === $node->_data );
	}

	/**
	 * @return Text
	 */
	protected function _subclassCloneNodeShallow(): Node {
		return new Text( $this->_nodeDocument, $this->_data );
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
		$newnode = $this->_nodeDocument->createTextNode( $newdata );
		$this->setNodeValue( substr( $this->_data, 0, $offset ) );

		$parent = $this->getParentNode();

		if ( $parent !== null ) {
			$parent->insertBefore( $newnode, $this->getNextSibling() );
		}
		return $newnode;
	}

	/**
	 * @return string
	 */
	public function wholeText() {
		$result = [ $this->getTextContent() ?? '' ];

		for ( $n = $this->getNextSibling(); $n !== null; $n = $n->getNextSibling() ) {
			if ( $n->getNodeType() !== Node::TEXT_NODE ) {
				break;
			}
			$result[] = $n->getTextContent() ?? '';
		}
		return implode( '', $result );
	}
}
