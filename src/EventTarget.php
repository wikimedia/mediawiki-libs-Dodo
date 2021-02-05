<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
// phpcs:disable Squiz.Scope.MethodScope.Missing

namespace Wikimedia\Dodo;

class EventTarget {
	/* XXX IMPLEMENT ME XXX */
	function _getEventHandler( $name ) {
		throw new \Error( "unimplemented" );
	}

	function _setEventHandler( $name, $handler ) {
		throw new \Error( "unimplemented" );
	}
}
