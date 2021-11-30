<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

trait HTMLHyperlinkElementUtils /* implements \Wikimedia\IDLeDOM\HTMLHyperlinkElementUtils */ {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\HTMLHyperlinkElementUtils;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\HTMLHyperlinkElementUtils;

	/*
	 * Don't inherit _getMissingProp from Helper/HTMLHyperlinkElementUtils,
	 * instead use the version from our parent class.
	 */

	/** @inheritDoc */
	abstract protected function _getMissingProp( string $prop );

	/** @inheritDoc */
	abstract protected function _setMissingProp( string $prop, $value ): void;
}
