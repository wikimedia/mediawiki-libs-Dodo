<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Zest\Zest;

trait ParentNode /* implements \Wikimedia\IDLeDOM\ParentNode */ {
	use \Wikimedia\IDLeDOM\Stub\ParentNode;

	/**
	 * @param string $selectors
	 * @return ?Element
	 */
	public function querySelector( string $selectors ) {
		$nodes = Zest::find( $selectors, $this );
		return $nodes[0] ?? null;
	}

	/**
	 * @param string $selectors
	 * @return NodeList
	 */
	public function querySelectorAll( string $selectors ) {
		$nl = new NodeList();
		$nl->_list = Zest::find( $selectors, $this );
		return $nl;
	}

}
