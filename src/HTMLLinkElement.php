<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;

class HTMLLinkElement extends HTMLElement implements \Wikimedia\IDLeDOM\HTMLLinkElement {
	use ReferrerPolicy;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\HTMLLinkElement;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\HTMLLinkElement;
}
