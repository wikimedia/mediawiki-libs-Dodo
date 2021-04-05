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
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\DOMImplementation;
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
	 * @param string $href
	 *
	 * @return DOMNode
	 */
	protected function parseHtmlToDom( string $href ) : DOMNode {
		$realpath = realpath( '.' );
		$file_path = iterator_to_array( ( new Finder() )->name( $href . '.html' )->in( realpath( '.' ) . '/tests/w3c' )
			->files()->sortByName() );
		$file = file_get_contents( array_key_first( $file_path ) );

		$domBuilder = new DOMBuilder( [ 'domImplementationClass' => DOMImplementation::class,
			'domExceptionClass' => DOMException::class ] );
		$treeBuilder = new TreeBuilder( $domBuilder );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher,
			$file );
		$tokenizer->execute();

		return $domBuilder->getFragment();
	}
}
