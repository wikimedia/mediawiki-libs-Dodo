<?php

declare( strict_types = 1 );
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * NodeList.php
 * ------------
 */
/* Played fairly straight. Used for Node::childNodes when in "array mode". */
class NodeList implements \Wikimedia\IDLeDOM\NodeList {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\NodeList;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\NodeList;

	/**
	 * @var array<Node> Backing storage for the NodeList
	 */
	private $_list = [];

	/** Create a new empty NodeList */
	public function __construct() {
	}

	/** @inheritDoc */
	public function item( int $i ): ?Node {
		return $this->_list[$i] ?? null;
	}
}
