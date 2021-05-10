<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;

class HTMLCollection implements \Wikimedia\IDLeDOM\HTMLCollection {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\HTMLCollection;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\HTMLCollection;
}
