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
	 * @param string $file_path
	 *
	 * @return DOMNode
	 */
	protected function parseHtmlToDom( string $file_path ) : DOMNode {
		$html = file_get_contents( $file_path );
		// This code will move into DOMParser::parseFromString eventually
		$domBuilder = new class( [ 'suppressHtmlNamespace' => true,
			'suppressIdAttribute' => true,
			'domExceptionClass' => DodoDOMException::class, ] ) extends DOMBuilder {
			/** @var DodoDOMDocument */
			private $doc;

			/** @inheritDoc */
			protected function createDocument( string $doctypeName = null,
				string $public = null, string $system = null ) {
				// Force this to be an HTML document (not an XML document)
				$this->doc = new DodoDOMDocument( null,
					'html' );

				return $this->doc;
			}

			/** @inheritDoc */
			public function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
				parent::doctype( $name,
					$public,
					$system,
					$quirks,
					$sourceStart,
					$sourceLength );
				// Set quirks mode on our document.
				switch ( $quirks ) {
					case TreeBuilder::NO_QUIRKS:
						$this->doc->_setQuirksMode( 'no-quirks' );
						break;
					case TreeBuilder::LIMITED_QUIRKS:
						$this->doc->_setQuirksMode( 'limited-quirks' );
						break;
					case TreeBuilder::QUIRKS:
						$this->doc->_setQuirksMode( 'quirks' );
						break;
				}
			}
		};
		$treeBuilder = new TreeBuilder( $domBuilder,
			[ 'ignoreErrors' => true ] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher,
			$html,
			[ 'ignoreErrors' => true ] );
		$tokenizer->execute( [] );

		return $domBuilder->getFragment();
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
}
