<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Exception;

/**
 * Helper trait that uses Dodo's UnimplementedException to fill out
 * the ::_unimplemented() abstract method from IDLeDOM's stubs.
 */
trait UnimplementedTrait {

	// Underscore is used to avoid conflicts with DOM-reserved names
	// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
	// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

	protected function _unimplemented() : Exception {
		return new UnimplementedException();
	}

	// phpcs:enable
}
