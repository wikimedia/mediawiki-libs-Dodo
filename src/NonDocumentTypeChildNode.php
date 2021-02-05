<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/******************************************************************************
 * NonDocumentTypeChildNode.php
 * ----------------------------
 */
/*
 * PORT NOTE: This, per spec, operates less like an inherited
 * class and more like a mixin. It's used by Element and CharacterData.
 */
abstract class NonDocumentTypeChildNode extends ChildNode {
	public function __construct() {
		parent::__construct();
	}

	public function nextElementSibling(): ?Element {
		if ( $this->_parentNode === null ) {
			return null;
		}

		for ( $n = $this->nextSibling(); $n !== null; $n = $n->nextSibling() ) {
			if ( $n->_nodeType === Node::ELEMENT_NODE ) {
				return $n;
			}
		}
		return null;
	}

	public function previousElementSibling(): ?Element {
		if ( $this->_parentNode === null ) {
			return null;
		}
		for ( $n = $this->previousSibling(); $n !== null; $n = $n->previousSibling() ) {
			if ( $n->_nodeType === Node::ELEMENT_NODE ) {
				return $n;
			}
		}
		return null;
	}
}
