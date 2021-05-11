<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\Util;

class HTMLElement extends Element implements \Wikimedia\IDLeDOM\HTMLElement {
	// DOM mixins
	use ElementContentEditable;
	use HTMLOrSVGElement;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\HTMLElement;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\HTMLElement;

	/**
	 * HTML Element constructor
	 *
	 * @param Document $doc
	 * @param string $lname
	 * @param ?string $prefix
	 * @return void
	 */
	public function __construct( Document $doc, string $lname, ?string $prefix = null ) {
		parent::__construct( $doc, $lname, Util::NAMESPACE_HTML, $prefix );
	}
}
