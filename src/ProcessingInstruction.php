<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * ProcessingInstruction.php
 * -------------------------
 */
class ProcessingInstruction extends CharacterData {
	protected const _nodeType = Node::PROCESSING_INSTRUCTION_NODE;

	/**
	 * @param Document $doc
	 * @param string $target
	 * @param mixed $data
	 */
	public function __construct( Document $doc, string $target, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_nodeName = $target; // spec
		$this->_target = $target;
		$this->_data = $data;
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

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function data( $value = null ) {
		return $this->nodeValue( $value );
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
