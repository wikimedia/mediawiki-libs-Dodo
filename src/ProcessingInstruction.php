<?php

declare( strict_types = 1 );
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps

namespace Wikimedia\Dodo;

/******************************************************************************
 * ProcessingInstruction.php
 * -------------------------
 */
class ProcessingInstruction extends CharacterData implements \Wikimedia\IDLeDOM\ProcessingInstruction {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\ProcessingInstruction;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\ProcessingInstruction;

	/**
	 * @param Document $doc
	 * @param string $target
	 * @param mixed $data
	 */
	public function __construct( Document $doc, string $target, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_target = $target;
		$this->_data = $data;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::PROCESSING_INSTRUCTION_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return $this->_target;
	}

	/**
	 * Overrides Node::nodeValue
	 * $value = '' will unset
	 *
	 * @param mixed $value
	 * @return mixed|void
	 */
	public function nodeValue( $value = null ) {
		if ( $value === null ) {
			return $this->_data;
		} else {
			$this->_data = strval( $value );
			if ( $this->__is_rooted ) {
				$this->_ownerDocument->__mutate_value( $this );
			}
		}
	}

	/**
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
	 * Delegated methods from Node
	 *
	 * @return ?Node always ProcessingInstruction
	 */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new ProcessingInstruction( $this->_ownerDocument, $this->_target, $this->_data );
	}

	/**
	 * @param Node $node
	 * @return bool
	 */
	public function _subclass_isEqualNode( Node $node ): bool {
		return ( $this->_target === $node->_target && $this->_data === $node->_data );
	}
}
