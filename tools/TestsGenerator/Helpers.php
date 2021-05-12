<?php

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall as MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use RemexHtml\DOM\DOMBuilder;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use Symfony\Component\Finder\Finder;
use Throwable;
use Wikimedia\Dodo\Document as DodoDOMDocument;
use Wikimedia\Dodo\DOMException as DodoDOMException;
use Wikimedia\Dodo\Node as DOMNode;

/**
 * Trait Helpers
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
	 * Loads html document.
	 *
	 * @param mixed $docRef
	 * @param string|null $name
	 * @param string|null $href
	 *
	 * @return DodoDOMDocument|null
	 */
	protected function load( $docRef = null, ?string $name = null, ?string $href = null ) : ?DOMNode {
		// Replace it with actual getElementsByTagName call.
		// Use this one after Remex integration is complete.
		$realpath = realpath( '.' );
		$file_path = iterator_to_array( ( new Finder() )->name( $href . '.html' )->in( realpath( '.' ) . '/tests/w3c' )
			->files()->sortByName() );

		return $this->parseHtmlToDom( array_key_first( $file_path ) );
	}

	/**
	 * Loads html document.
	 *
	 * @param mixed $docRef
	 *
	 * @return DodoDOMDocument|null
	 */
	protected function loadWptHtmlFile( $docRef ) : ?DOMNode {
		return $this->parseHtmlToDom( realpath( '.' ) . '/' . $docRef );
	}

	/**
	 * @param string $file_path
	 *
	 * @return DOMNode
	 */
	protected function parseHtmlToDom( string $file_path ) : DOMNode {
		$html = file_get_contents( $file_path );
		$domImpl = ( new DodoDOMDocument( null, 'html' ) )->getImplementation();
		$domBuilder = new DOMBuilder( [
			'suppressHtmlNamespace' => true,
			'suppressIdAttribute' => true,
			'domImplementation' => $domImpl,
			'domExceptionClass' => DodoDOMException::class,
		] );
		$treeBuilder = new TreeBuilder( $domBuilder, [
			'ignoreErrors' => true
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $html, [
				'ignoreErrors' => true ]
		);
		$tokenizer->execute( [] );

		return $domBuilder->getFragment();
	}
}
