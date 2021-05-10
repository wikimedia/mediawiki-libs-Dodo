<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;

class HTMLElement extends Element implements \Wikimedia\IDLeDOM\HTMLElement {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\HTMLElement;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\HTMLElement;
}
