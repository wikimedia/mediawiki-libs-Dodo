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
	 * @param string $data
	 */
	public function __construct( Document $doc, string $target, string $data ) {
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
