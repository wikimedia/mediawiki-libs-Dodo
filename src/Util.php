<?php

declare( strict_types = 1 );
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable PSR12.Properties.ConstantVisibility.NotFound

namespace Wikimedia\Dodo;

/******************************************************************************
 * Util.php
 * -------------
 * Define namespace-global constants and functions (mostly error-handling).
 */
class Util {

	/******************************************************************************
	 * CONSTANTS
	 * ---------
	 * The various W3C and WHATWG recommendations define a number of
	 * constants. Although these are usually associated with a particular
	 * interface, we collect all of them here for convenience.
	 */

	/**
	 * NAMESPACE_*
	 * Strings defining the various document namespaces
	 * [DODO] These are used by this library and aren't part of a spec.
	 */
	const NAMESPACE_HTML = "http://www.w3.org/1999/xhtml";
	const NAMESPACE_XML = "http://www.w3.org/XML/1998/namespace";
	const NAMESPACE_XMLNS = "http://www.w3.org/2000/xmlns/";
	const NAMESPACE_MATHML = "http://www.w3.org/1998/Math/MathML";
	const NAMESPACE_SVG = "http://www.w3.org/2000/svg";
	const NAMESPACE_XLINK = "http://www.w3.org/1999/xlink";

	/**
	 * Original:
	 * throw new Error("Assertion failed: " + (msg || "") + "\n" new Error().stack);
	 *
	 * TODO: Need to add the stack trace info, or advise catchers call
	 * Exception::getTraceAsString()
	 *
	 * TODO: Make this a true PHP assert?
	 *
	 * @param bool $condition
	 * @param ?string $message
	 * @throws \Exception
	 */
	public static function assert( bool $condition, ?string $message = "" ) {
		if ( !$condition ) {
			throw new \Exception( "Assert failed: $message" );
		}
	}

	/**
	 * Throw a DOMException
	 *
	 * @param string $name one of the values below
	 * @param string|null $message an optional message to include in the Exception
	 * @return void
	 * @throws DOMException
	 *
	 * NOTE
	 * Allowed values for $string are: IndexSizeError, HierarchyRequestError
	 * WrongDocumentError, InvalidCharacterError, NoModificationAllowedError,
	 * NotFoundError, NotSupportedError, InvalidStateError, SyntaxError,
	 * InvalidModificationError, NamespaceError, InvalidAccessError,
	 * TypeMismatchError, SecurityError, NetworkError, AbortError,
	 * UrlMismatchError, QuotaExceededError, TimeoutError,
	 * InvalidNodeTypeError, and DataCloneError
	 *
	 * For more information, see interfaces/DOMException.php
	 */
	public static function error( string $name, ?string $message = null ) {
		throw new DOMException( $message, $name );
	}

	/******************************************************************************
	 * TEXT FORMATTING
	 */

	/**
	 * TODO: Why? I don't know. strtolower()/strtoupper() don't do the right thing
	 * for non-ASCII characters, and mb_strtolower()/mb_strtoupper() are up
	 * to 30x slower. But these are only called on things that should accept
	 * only ASCII values to begin with (e.g. attribute names in HTML). So -- why?
	 *
	 * @param string $s
	 * @return string
	 */
	public static function ascii_to_lowercase( string $s ): string {
		return preg_replace_callback( '/[A-Z]+/', function ( $char ) {
			return strtolower( $char );
		}, $s );
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public static function ascii_to_uppercase( string $s ): string {
		return preg_replace_callback( '/[a-z]+/', function ( $char ) {
			return strtoupper( $char );
		}, $s );
	}
}
