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
	use \Wikimedia\IDLeDOM\Helper\Text {
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
	 * @param Document $nodeDocument
	 * @param string $data
	 */
	public function __construct( Document $nodeDocument, string $data ) {
		parent::__construct( $nodeDocument, $data );
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
	 * @param int $offset Offset *in UTF-16 code units*
	 * @return Text
	 */
	public function splitText( $offset ) {
		$data = $this->_getDataUTF16();
		if ( $offset * 2 > strlen( $data ) || $offset < 0 ) {
			Util::error( "IndexSizeError" );
		}

		$newdata = substr( $data, $offset * 2 );
		// Avoid converting newdata to UTF8 if we don't need to
		$newnode = new Text( $this->_nodeDocument, $newdata );
		$newnode->_isUtf16 = true;
		// Same: this is already UTF-16 thanks to the _getDataUTF16 call above
		$this->_data = substr( $data, 0, $offset * 2 );

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
