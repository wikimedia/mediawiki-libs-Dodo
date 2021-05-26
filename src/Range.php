<?php

namespace Wikimedia\Dodo;

use Wikimedia\IDLeDOM\DocumentFragment;
use Wikimedia\IDLeDOM\Node;

class Range extends AbstractRange implements \Wikimedia\IDLeDOM\Range {

	/**
	 * @return void|Node
	 */
	public function getCommonAncestorContainer() {
		return $this->commonAncestorContainer;
	}

	/**
	 * @param Node $node
	 * @param int $offset
	 */
	public function setStart( $node, int $offset ) : void {
		// TODO: Implement setStart() method.
	}

	/**
	 * @param Node $node
	 * @param int $offset
	 */
	public function setEnd( $node, int $offset ) : void {
		// TODO: Implement setEnd() method.
	}

	/**
	 * @param Node $node
	 */
	public function setStartBefore( $node ) : void {
		// TODO: Implement setStartBefore() method.
	}

	/**
	 * @param Node $node
	 */
	public function setStartAfter( $node ) : void {
		// TODO: Implement setStartAfter() method.
	}

	/**
	 * @param Node $node
	 */
	public function setEndBefore( $node ) : void {
		// TODO: Implement setEndBefore() method.
	}

	/**
	 * @param Node $node
	 */
	public function setEndAfter( $node ) : void {
		// TODO: Implement setEndAfter() method.
	}

	/**
	 * @param bool $toStart
	 */
	public function collapse( bool $toStart = false ) : void {
		// TODO: Implement collapse() method.
	}

	/**
	 * @param Node $node
	 */
	public function selectNode( $node ) : void {
		// TODO: Implement selectNode() method.
	}

	/**
	 * @param Node $node
	 */
	public function selectNodeContents( $node ) : void {
		// TODO: Implement selectNodeContents() method.
	}

	/**
	 * @param int $how
	 * @param \Wikimedia\IDLeDOM\Range $sourceRange
	 *
	 * @return int
	 */
	public function compareBoundaryPoints( int $how, $sourceRange ) : int {
		return 0;
	}

	/**
	 *
	 */
	public function deleteContents() : void {
		// TODO: Implement deleteContents() method.
	}

	/**
	 * @return void|DocumentFragment
	 */
	public function extractContents() {
		// TODO: Implement extractContents() method.
	}

	/**
	 * @return void|DocumentFragment
	 */
	public function cloneContents() {
		// TODO: Implement cloneContents() method.
	}

	/**
	 * @param Node $node
	 */
	public function insertNode( $node ) : void {
		// TODO: Implement insertNode() method.
	}

	/**
	 * @param Node $newParent
	 */
	public function surroundContents( $newParent ) : void {
		// TODO: Implement surroundContents() method.
	}

	/**
	 * @return void|\Wikimedia\IDLeDOM\Range
	 */
	public function cloneRange() {
		// TODO: Implement cloneRange() method.
	}

	/**
	 *
	 */
	public function detach() : void {
		// TODO: Implement detach() method.
	}

	/**
	 * @param Node $node
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function isPointInRange( $node, int $offset ) : bool {
		return true;
	}

	/**
	 * @param Node $node
	 * @param int $offset
	 *
	 * @return int
	 */
	public function comparePoint( $node, int $offset ) : int {
		return 0;
	}

	/**
	 * @param Node $node
	 *
	 * @return bool
	 */
	public function intersectsNode( $node ) : bool {
		return true;
	}

	/**
	 * @return string
	 */
	public function toString() : string {
		return '';
	}

	/**
	 * @return string
	 */
	public function __toString() : string {
		return $this->toString();
	}

	/**
	 * @param string $fragment
	 *
	 * @return void|DocumentFragment
	 */
	public function createContextualFragment( string $fragment ) {
		// TODO: Implement createContextualFragment() method.
	}
}
