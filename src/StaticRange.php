<?php

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\Util;

/**
 * Returns a new range object that does not update when the node tree mutates.
 *
 * @package Wikimedia\Dodo
 */
class StaticRange extends AbstractRange {
	use \Wikimedia\IDLeDOM\Helper\StaticRange;

	/**
	 * The new StaticRange(init) constructor steps are:
	 * If init["startContainer"] or init["endContainer"] is a DocumentType or Attr node, then throw an
	 * "InvalidNodeTypeError" DOMException.
	 * Set this’s start to (init["startContainer"], init["startOffset"]) and end to (init["endContainer"],
	 * init["endOffset"]).
	 *
	 * @param array $init
	 */
	public function __construct( array $init ) {
		if ( $init['startContainer'] instanceof DocumentType || $init['startContainer'] instanceof Attr ) {
			Util::error( 'InvalidNodeTypeError' );
		}

		if ( $init['endContainer'] instanceof DocumentType || $init['endContainer'] instanceof Attr ) {
			Util::error( 'InvalidNodeTypeError' );
		}

		$this->startContainer = $init['startContainer'];
		$this->endContainer = $init['endContainer'];
		$this->startOffset = $init['startOffset'];
		$this->endOffset = $init['endOffset'];
	}

	/**
	 * TODO implement this
	 *
	 * Returns a new Range object which describes the same range as the source StaticRange,
	 * but is "live" with values that change to reflect changes in the contents of the DOM tree.
	 *
	 * @return Range
	 */
	public function toRange() : Range {
		return new Range();
	}
}
