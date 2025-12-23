<?php
/** @phan-file-suppress PhanRedefineClassAlias, PhanImpossibleCondition */
declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/**
 * HTMLDocument doesn't actually exist in the DOM spec, except as a legacy
 * alias for Document.
 * @see https://developer.mozilla.org/en-US/docs/Web/API/HTMLDocument
 */
class_alias( Document::class, HTMLDocument::class );

// phpcs:ignore Generic.CodeAnalysis.UnconditionalIfStatement.Found
if ( false ) {
	/**
	 * This is needed for classmap-authoritative support (T409283)
	 * This should be re-evaluated once support for PHP 8.3 is dropped
	 */
	class HTMLDocument {
	}
}
