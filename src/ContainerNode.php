<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\WhatWG;

/**
 * The ContainerNode class defines common functionality for node subtypes
 * that can have children.  We factor this out so that leaf nodes can
 * save the space required to maintain the list of children.  There are
 * a lot of leaf nodes in a document, so this space can add up.
 *
 * The list of children is kept primarily as a circular linked list of
 * siblings.  We fail over to an array of children (which makes
 * insertion and removal much more expensive) only when required.
 */
abstract class ContainerNode extends Node {
	/**
	 * @var Node|NodeList|null The first child (if we're using the linked
	 *  list representation), or a NodeList (if we're using the array of
	 *  children), or null (if there are no children).
	 */
	public $_firstChildOrChildren;

	/**
	 * @param Document $nodeDocument
	 */
	public function __construct( Document $nodeDocument ) {
		parent::__construct( $nodeDocument );
		$this->_firstChildOrChildren = null; // No children
	}

	/**
	 * Return true iff there are children of this node.
	 *
	 * @inheritDoc
	 */
	public function hasChildNodes(): bool {
		$kids = $this->_firstChildOrChildren;
		// Common case is "no child nodes and linked list representation",
		// so test that first.
		if ( $kids === null ) {
			return false;
		}
		// Check to see if we're using the array representation.
		if ( $kids instanceof NodeList ) {
			return ( $kids->getLength() > 0 );
		}
		// We're using the linked list representation and the list isn't empty.
		return true;
	}

	/** @inheritDoc */
	public function _length(): int {
		if ( $this->_firstChildOrChildren === null ) {
			// Don't force conversion to array form for this common case.
			return 0;
		}
		// Convert to array form because we're probably going to iterate
		// over the nodes by index after this returns.
		return $this->getChildNodes()->getLength();
	}

	/** @inheritDoc */
	public function _empty(): bool {
		return !$this->hasChildNodes();
	}

	/**
	 * Keeping child nodes as an array makes insertion/removal of nodes
	 * quite expensive.  So we try *never* to create this array, if
	 * possible.  If someone
	 * actually fetches the childNodes list we lazily create it.
	 * It then has to be live, and so we must update it whenever
	 * nodes are appended or removed.
	 *
	 * @inheritDoc
	 */
	public function getChildNodes(): NodeList {
		if ( !( $this->_firstChildOrChildren instanceof NodeList ) ) {
			// If childNodes has never been created, we now create it.
			$first = $this->_firstChildOrChildren;
			$this->_firstChildOrChildren = $childNodes = new NodeList();
			// optimized circular linked list traversal
			$kid = $first;
			if ( $kid !== null ) {
				do {
					$childNodes->_append( $kid );
					$kid = $kid->_nextSibling;
				} while ( $kid !== $first ); // circular linked list
			}
		}
		return $this->_firstChildOrChildren;
	}

	/**
	 * Be careful to use this method in most cases rather than directly
	 * accessing `_firstChildOrChildren`.
	 *
	 * @inheritDoc
	 */
	public function getFirstChild(): ?Node {
		$kids = $this->_firstChildOrChildren;
		if ( !( $kids instanceof NodeList ) ) {
			/*
			 * If we are using the Linked List representation, then just return
			 * the backing property (may still be null).
			 */
			return $kids;
		}
		$len = $kids->getLength();
		return $len === 0 ? null : $kids->item( 0 );
	}

	/**
	 * @inheritDoc
	 */
	public function getLastChild(): ?Node {
		$kids = $this->_firstChildOrChildren;
		if ( $kids instanceof NodeList ) {
			// We are using the NodeList representation.
			$len = $kids->getLength();
			return $len === 0 ? null : $kids->item( $len - 1 );
		}
		// We are using the Linked List representation.
		if ( $kids === null ) {
			return null;
		}
		// If we have a firstChild, its _previousSibling is the last child,
		// because this is a circularly linked list.
		return $kids->_previousSibling;
	}

	// These next methods are defined on Element and DocumentFragment with
	// identical behavior.  Note that they are defined differently on Document,
	// however, so we need to override this definition in that class.

	/**
	 * Generic implementation of ::getTextContent to be used by Element
	 * and DocumentFragment (but not Document!).
	 * @see https://dom.spec.whatwg.org/#dom-node-textcontent
	 * @return ?string
	 */
	public function getTextContent(): ?string {
		$text = [];
		WhatWG::descendantTextContent( $this, $text );
		return implode( "", $text );
	}

	/**
	 * Generic implementation of ::setTextContent to be used by Element
	 * and DocumentFragment (but not Document!).
	 * @see https://dom.spec.whatwg.org/#dom-node-textcontent
	 * @param ?string $value
	 */
	public function setTextContent( ?string $value ): void {
		$value ??= '';
		$this->_removeChildren();
		if ( $value !== "" ) {
			/* Equivalent to Node:: appendChild without checks! */
			$this->_unsafeAppendChild(
				$this->_nodeDocument->createTextNode( $value )
			);
		}
	}

}
