<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingReturn
// phpcs:disable MediaWiki.Commenting.FunctionComment.SpacingAfter
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/*
 * DOM-LS specifies that in the
 * event that two Elements have
 * the same 'id' attribute value,
 * the first one, in document order,
 * shall be returned from getElementById.
 *
 * This data structure makes that
 * as performant as possible, by:
 *
 * 1. Caching the first element in the list, in document order
 * It is updated on move because a move is treated as a
 * removal followed by an insertion, and those two operations
 * will update this table.
 *
 * 2. Elements are looked up by an integer index set when they
 * are adopted by Document. This index gives a canonical
 * integer representation of an Element, so we can operate
 * on integers instead of Elements.
 */
class MultiId {
	public $table = [];
	public $length = 0;
	/*
	 * The first element,
	 * in document order.
	 * NULL indicates the
	 * cache is not set
	 * and the first
	 * element must be
	 * re-computed.
	 */
	public $first = null;

	public function __construct( Node $node ) {
		$this->table[$node->__document_index] = $node;
		$this->length = 1;
		$this->first = null;
	}

	/*
	 * Add a Node to array
	 * in O(1) time by using
	 * Node::$__document_index
	 * as the array index.
	 */
	public function add( Node $node ) {
		if ( !isset( $this->table[$node->__document_index] ) ) {
			$this->table[$node->__document_index] = $node;
			$this->length++;
			$this->first = null; /* invalidate cache */
		}
	}

	/*
	 * Remove a Node from
	 * the array in O(1)
	 * time by using
	 * Node::$__document_index
	 * to perform the lookup.
	 */
	public function del( Node $node ) {
		if ( $this->table[$node->__document_index] ) {
			unset( $this->table[$node->__document_index] );
			$this->length--;
			$this->first = null; /* invalidate cache */
		}
	}

	/*
	 * Retreive that Node
	 * from the array which
	 * appears first in
	 * document order in
	 * the associated document.
	 *
	 * Cache the value for
	 * repeated lookups.
	 *
	 * The cache is invalidated
	 * each time the array
	 * is modified. The list
	 * is modified when a Node
	 * is inserted or removed
	 * from a Document, or when
	 * the 'id' attribute value
	 * of a Node is changed.
	 */
	public function get_first() {
		if ( $this->first === null ) {
			/*
			 * No item has been cached.
			 * Well, let's find it then.
			 */
			foreach ( $this->table as $document_index => $node ) {
				if ( $this->first === null || $this->first->compareDocumentPosition( $node ) & Util::DOCUMENT_POSITION_PRECEDING ) {
					$this->first = $node;
				}
				/* TODO: What about the old NULLity stuff?? */
				//if ($this->first === NULL || $this->first->compareDocumentPosition($node) & DOCUMENT_POSITION_PRECEDING) {
				//$this->first = $node;
				//}
			}
		}
		return $this->first;
	}

	/*
	 * If there is only one node left, return it. Otherwise return "this".
	 */
	public function downgrade() {
		if ( $this->length === 1 ) {
			foreach ( $this->table as $document_index => $node ) {
				return $node;
			}
		}
		return $this;
	}
}
