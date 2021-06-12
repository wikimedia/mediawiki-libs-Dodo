<?php

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall as MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Throwable;
use Wikimedia\Dodo\Document as DodoDOMDocument;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Node as DOMNode;

/**
 * Containes general helpful methods.
 *
 * @package Wikimedia\Dodo\Tools\TestsGenerator
 */
trait Helpers {
	/**
	 * Converts snake case to camel case.
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	protected function snakeToCamel( string $input ) : string {
		$patterns = [ '_' => ' ',
			'-' => ' ',
			'.' => ' ' ];

		return lcfirst( str_replace( ' ',
			'',
			ucwords( strtr( $input,
				$patterns ) ) ) );
	}

	/**
	 * Converts snake case to pascal case.
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	protected function snakeToPascal( string $input ) : string {
		$patterns = [ '_' => ' ',
			'-' => ' ',
			'.' => ' ' ];

		return ucfirst( str_replace( ' ',
			'',
			ucwords( strtr( $input,
				$patterns ) ) ) );
	}

	/**
	 * Adds expression to AST
	 *
	 * @param string $type
	 * @param array $args
	 * @param array $attributes
	 *
	 * @return Expression
	 */
	protected function addExpectation( string $type, $args = [], $attributes = [] ) : Expression {
		return new Expression( new MethodCall( new Variable( 'this' ),
			$type,
			[ new Arg( new String_( Throwable::class ) ) ],
			$attributes ) );
	}

	/**
	 * Parses HTML file using RemexHTML.
	 *
	 * @param string $file_path
	 *
	 * @return DOMNode
	 */
	protected function parseHtmlToDom( string $file_path ) : DOMNode {
		$html = file_get_contents( $file_path );
		$parser = new DOMParser();
		$doc = $parser->parseFromString( $html, "text/html" );
		return $doc;
	}

	/**
	 * TODO test this
	 * Parses HTML file using RemexHTML.
	 *
	 * @param string $file_path
	 *
	 * @return DOMNode
	 */
	protected function parseXMLToDom( string $file_path ) : DOMNode {
		$html = file_get_contents( $file_path );
		$parser = new DOMParser();
		$doc = $parser->parseFromString( $html, "text/xml" );
		return $doc;
	}

	/**
	 * Loads html document. Used by WPT tests.
	 *
	 * @param mixed $docRef
	 *
	 * @return DodoDOMDocument|null
	 */
	protected function loadWptHtmlFile( $docRef ) : ?DOMNode {
		return $this->parseHtmlToDom( realpath( '.' ) . '/' . $docRef );
	}
}
