<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic

/******************************************************************************
 * NodeList.php
 * ------------
 */
/* Played fairly straight. Used for Node::childNodes when in "array mode". */
class NodeList extends \ArrayObject {
	public function __construct( $input = null ) {
		parent::__construct( $input );
	}

	public function item( $i ) {
		return $this[$i] ?? null;
	}
}
