<?php

declare( strict_types = 1 );
// XXX Should fix these!
// @phan-file-suppress PhanUndeclaredClassMethod
// @phan-file-suppress PhanUndeclaredFunction
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredStaticMethod
// @phan-file-suppress PhanTypeMismatchArgument
// @phan-file-suppress PhanTypeMismatchArgumentReal
// @phan-file-suppress PhanTypeExpectedObjectPropAccess

namespace Wikimedia\Dodo\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use RemexHtml\DOM\DOMBuilder;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use stdClass;
use Symfony\Component\Finder\Finder;
use Wikimedia\Dodo\Document as DodoDOMDocument;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Node as Node;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;

/**
 * Class DomTestCase, used for converted DominoJS W3C tests.
 *
 * @see DomTestCase.js
 * @package Wikimedia\Dodo\Tests
 */
abstract class DomTestCase extends TestCase {
	use Helpers;

	/**
	 * @var DodoDOMDocument
	 */
	protected $doc;

	/**
	 * @var string
	 */
	protected $contentType;

	/**
	 * @param string $message
	 * @param string|null $actual
	 */
	public function assertNullData( string $message, ?string $actual ) : void {
		$this->assertNull( $actual,
			$message );
	}

	/**
	 *
	 * @param string $message
	 * @param string $actual
	 */
	public function assertFalseData( string $message, string $actual ) : void {
		$this->assertFalse( !$actual,
			$message );
	}

	/**
	 * @param string $message
	 * @param string $actual
	 */
	public function assertTrueData( string $message, string $actual ) : void {
		$this->assertTrue( $actual,
			$message );
	}

	/**
	 * @param string $descr
	 * @param int $expected
	 * @param mixed $actual
	 */
	public function assertSizeData( string $descr, int $expected, $actual ) : void {
		$this->assertNotNullData( $descr,
			$actual );
		$actualSize = count( $actual );
		$this->assertEqualsData( $descr,
			$expected,
			$actualSize );
	}

	/**
	 *
	 * @param string $message
	 * @param mixed $actual
	 */
	public function assertNotNullData( string $message, $actual ) : void {
		$this->assertNotEquals( null,
			$actual,
			$message );
	}

	/**
	 * @param string $message
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsData( string $message, string $expected, string $actual ) : void {
		$this->assertEquals( $expected,
			$actual,
			$message );
	}

	/**
	 * @todo replace assert
	 *
	 * @param string $context
	 * @param string $descr
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsCollectionAutoCaseData( string $context, string $descr,
		string $expected, string $actual ) : void {
		//
		// if they aren't the same size, they aren't equal
		$this->assertEqualsData( $descr,
			strlen( $expected ),
			strlen( $actual ) );

		// if there length is the same, then every entry in the expected list
		// must appear once and only once in the actual list
		$expectedLen = strlen( $expected );
		$expectedValue = null;
		$actualLen = strlen( $actual );
		$i = null;
		$j = null;
		$matches = null;
		for ( $i = 0; $i < $expectedLen; $i++ ) {
			$matches = 0;
			$expectedValue = $expected[$i];
			for ( $j = 0; $j < $actualLen; $j++ ) {
				if ( $this->contentType == 'text/html' ) {
					if ( $context == 'attribute' ) {
						if ( strtolower( $expectedValue ) == strtolower( $actual[$j] ) ) {
							$matches++;
						}
					} else {

						if ( strtoupper( $expectedValue ) == $actual[$j] ) {
							$matches++;
						}
					}
				} else {
					if ( $expectedValue == $actual[$j] ) {
						$matches++;
					}
				}
			}
			if ( $matches == 0 ) {
				// assert( $descr . ': No match found for ' . $expectedValue, false );
			}
			if ( $matches > 1 ) {
				// assert( $descr . ': Multiple matches found for ' . $expectedValue, false );
			}
		}
	}

	/**
	 * @todo replace assert
	 *
	 * @param string $descr
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsCollectionData( string $descr, string $expected, string $actual ) {
		//
		// if they aren't the same size, they aren't equal
		$this->assertEqualsData( $descr,
			strlen( $expected ),
			strlen( $actual ) );
		//
		// if there length is the same, then every entry in the expected list
		// must appear once and only once in the actual list
		$expectedLen = strlen( $expected );
		$expectedValue = null;
		$actualLen = strlen( $actual );
		$i = null;
		$j = null;
		$matches = null;
		for ( $i = 0; $i < $expectedLen; $i++ ) {
			$matches = 0;
			$expectedValue = $expected[$i];
			for ( $j = 0; $j < $actualLen; $j++ ) {
				if ( $expectedValue == $actual[$j] ) {
					$matches++;
				}
			}
			if ( $matches == 0 ) {
				// assert( $descr . ': No match found for ' . $expectedValue, false );
			}
			if ( $matches > 1 ) {
				// assert( $descr . ': Multiple matches found for ' . $expectedValue, false );
			}
		}
	}

	/**
	 * @param string $context
	 * @param string $descr
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsListAutoCaseData( string $context, string $descr, string $expected, string $actual ) {
		$minLength = strlen( $expected );
		if ( strlen( $actual ) < $minLength ) {
			$minLength = strlen( $actual );
		}
		//
		for ( $i = 0; $i < $minLength; $i++ ) {
			$this->assertEqualsAutoCaseData( $context,
				$descr,
				$expected[$i],
				$actual[$i] );
		}
		//
		// if they aren't the same size, they aren't equal
		$this->assertEqualsData( $descr,
			strlen( $expected ),
			strlen( $actual ) );
	}

	/**
	 * @param string $context
	 * @param string $descr
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsAutoCaseData( string $context, string $descr, string $expected, string $actual ) {
		if ( $this->contentType == 'text/html' ) {
			if ( $context == 'attribute' ) {
				$this->assertEqualsData( $descr,
					strtolower( $expected ),
					strtolower( $actual ) );
			} else {
				$this->assertEqualsData( $descr,
					strtoupper( $expected ),
					$actual );
			}
		} else {
			$this->assertEqualsData( $descr,
				$expected,
				$actual );
		}
	}

	/**
	 * @param string $descr
	 * @param string $expected
	 * @param string $actual
	 */
	public function assertEqualsListData( string $descr, string $expected, string $actual ) {
		$minLength = strlen( $expected );
		if ( strlen( $actual ) < $minLength ) {
			$minLength = strlen( $actual );
		}

		for ( $i = 0; $i < $minLength; $i++ ) {
			if ( $expected[$i] != $actual[$i] ) {
				$this->assertEqualsData( $descr,
					$expected[$i],
					$actual[$i] );
			}
		}
		// if they aren't the same size, they aren't equal
		$this->assertEqualsData( $descr,
			strlen( $expected ),
			strlen( $actual ) );
	}

	/**
	 * @param string $descr
	 * @param string $type
	 * @param string $obj
	 */
	public function assertInstanceOfData( string $descr, string $type, string $obj ) {
		if ( $type == 'Attr' ) {
			$this->assertEqualsData( $descr,
				2,
				$obj->nodeType );
			// What's this ?
			$specd = $obj->specified;
		}
	}

	/**
	 * @param string $descr
	 * @param Node $expected
	 * @param Node $actual
	 */
	public function assertSameData( string $descr, Node $expected, Node $actual ) {
		if ( $expected !== $actual ) {
			$this->assertEqualsData( $descr,
				$expected->nodeType,
				$actual->nodeType );
			$this->assertEqualsData( $descr,
				$expected->nodeValue,
				$actual->nodeValue );
		}
	}

	/**
	 * @param string $assertID
	 * @param string $scheme
	 * @param string $path
	 * @param string $host
	 * @param string $file
	 * @param string $name
	 * @param string $query
	 * @param string $fragment
	 * @param string $isAbsolute
	 * @param string $actual
	 */
	public function assertURIEqualsData( string $assertID, string $scheme, string $path, string $host,
		string $file, string $name, string $query, string $fragment, string $isAbsolute, string $actual ) {
		//
		// URI must be non-null
		$this->assertNotNullData( $assertID,
			$actual );

		$uri = $actual;

		$lastPound = strrpos( $actual,
			'#' );
		$actualFragment = '';
		if ( $lastPound != -1 ) {
			//
			//  substring before pound
			//
			$uri = substr( $actual,
				0,
				$lastPound/*CHECK THIS*/ );
			$actualFragment = substr( $actual,
				$lastPound + 1 );
		}
		if ( $fragment != null ) {
			$this->assertEqualsData( $assertID,
				$fragment,
				$actualFragment );
		}

		$lastQuestion = strrpos( $uri,
			'?' );
		$actualQuery = '';
		if ( $lastQuestion != -1 ) {
			//
			//  substring before pound
			//
			$uri = substr( $actual,
				0,
				$lastQuestion/*CHECK THIS*/ );
			$actualQuery = substr( $actual,
				$lastQuestion + 1 );
		}
		if ( $query != null ) {
			$this->assertEqualsData( $assertID,
				$query,
				$actualQuery );
		}

		$firstColon = strpos( $uri,
			':' );
		$firstSlash = strpos( $uri,
			'/' );
		$actualPath = $uri;
		$actualScheme = '';
		if ( $firstColon != -1 && $firstColon < $firstSlash ) {
			$actualScheme = substr( $uri,
				0,
				$firstColon/*CHECK THIS*/ );
			$actualPath = substr( $uri,
				$firstColon + 1 );
		}

		if ( $scheme != null ) {
			$this->assertEqualsData( $assertID,
				$scheme,
				$actualScheme );
		}

		if ( $path != null ) {
			$this->assertEqualsData( $assertID,
				$path,
				$actualPath );
		}

		if ( $host != null ) {
			$actualHost = '';
			// TODO: Refactor this.
			if ( substr( $actualPath,
					0,
					2/*CHECK THIS*/ ) == '//' ) {
				// TODO Test this!.
				$termSlash = strpos( substr( $actualPath,
						2 ),
						'/' ) + 2;
				$actualHost = substr( $actualPath,
					0,
					$termSlash/*CHECK THIS*/ );
			}
			assertEquals( $assertID,
				$host,
				$actualHost );
		}

		if ( $file != null || $name != null ) {
			$actualFile = $actualPath;
			$finalSlash = strrpos( $actualPath,
				'/' );
			if ( $finalSlash != -1 ) {
				$actualFile = substr( $actualPath,
					$finalSlash + 1 );
			}
			if ( $file != null ) {
				$this->assertEqualsData( $assertID,
					$file,
					$actualFile );
			}
			if ( $name != null ) {
				$actualName = $actualFile;
				$finalDot = strrpos( $actualFile,
					'.' );
				if ( $finalDot != -1 ) {
					$actualName = substr( $actualName,
						0,
						$finalDot/*CHECK THIS*/ );
				}
				$this->assertEqualsData( $assertID,
					$name,
					$actualName );
			}
		}

		if ( $isAbsolute != null ) {
			$this->assertEqualsData( $assertID . ' ' . $actualPath,
				$isAbsolute,
				substr( $actualPath,
					0,
					1/*CHECK THIS*/ ) == '/' );
		}
	}

	/**
	 * @param mixed $expected
	 * @param mixed $actual
	 *
	 * @return bool
	 */
	public function same( $expected, $actual ) : bool {
		return $expected === $actual;
	}

	/**
	 * @param string $contentType
	 *
	 * @return string
	 */
	public function getSuffix( string $contentType ) {
		switch ( $contentType ) {
			case 'text/html':
				return '.html';

			case 'text/xml':
				return '.xml';

			case 'application/xhtml+xml':
				return '.xhtml';

			case 'image/svg+xml':
				return '.svg';

			case 'text/mathml':
				return '.mml';
		}

		return '.html';
	}

	public function generateElement() {
		$this->generateDoc();
	}

	protected function generateDoc() {
		$this->doc = new DodoDOMDocument( 'html' );
		$html = $this->doc->createElement( 'html' );
		$title = $this->doc->createElement( 'title' );
		$title->appendChild( $this->doc->createTextNode( 'NIST DOM HTML Test - Anchorasd fadsfadsf' ) );
		$html->appendChild( $title );
		$body = $this->doc->createElement( 'body' );

		$html->appendChild( $body );
		$this->doc->appendChild( $html );

		$this->contentType = 'text/html';
	}

	/**
	 *
	 */
	public function executeBeforeFirstTest() : void {
		// called before the first test is being run
	}

	public function executeAfterLastTest() : void {
		// called after the last test has been run
	}

	/**
	 * @param string $error
	 *
	 * @throws DOMException
	 */
	public function makeFailed( string $error ) {
		if ( $error == 'throw_HIER_OR_NO_MOD_ERR' ) {
			throw new DOMException( 'throw_HIER_OR_NO_MOD_ERR',
				'NoModificationAllowedError' );
		}
	}

	/**
	 *
	 */
	protected function setUp() : void {
		$doc = Mockery::mock( DodoDOMDocument::class,
			[ 'html' ] )->makePartial();
		$doc->shouldReceive( 'getElementsByTagName' )->andReturnUsing( function ( $arg ) use ( $doc ) {
			// Return empty element. Temporary stub.
			return [ $doc->createElement( $arg ) ];
		} );

		$html = $doc->createElement( 'html' );
		$title = $doc->createElement( 'title' );
		$title->appendChild( $doc->createTextNode( 'NIST DOM HTML Test - Anchor' ) );
		$html->appendChild( $title );
		$body = $doc->createElement( 'body' );

		$html->appendChild( $body );
		$doc->appendChild( $html );
		$this->contentType = 'text/html';
		$this->doc = $doc;
	}

	/**
	 * hc_nodtdstaff.html
	 */
	protected function generateHcNodtdstaff() {
		$this->generateDoc();
	}

	/**
	 * hc_staff.html
	 */
	protected function generateHcStaff() {
		$this->generateDoc();
	}

	/**
	 * hc_staff.html
	 */
	protected function generateAnchor() {
		$this->doc = new DodoDOMDocument( 'html' );
		$html = $this->doc->createElement( 'html' );
		$title = $this->doc->createElement( 'title' );
		$title->appendChild( $this->doc->createTextNode( 'NIST DOM HTML Test - Anchorasd fadsfadsf' ) );
		$html->appendChild( $title );
		$body = $this->doc->createElement( 'body' );

		$html->appendChild( $body );
		$this->doc->appendChild( $html );

		$this->contentType = 'text/html';
	}

	/**
	 * hc_staff.html
	 */
	protected function generateAnchor2() {
		$this->generateDoc();
	}

	/**
	 * hc_staff.html
	 */
	protected function generateForm() {
		$this->generateDoc();
	}

	/**
	 * hc_staff.html
	 */
	protected function generateLink() {
		$this->generateDoc();
	}

	/**
	 * hc_staff.html
	 */
	protected function generateMod() {
		$this->generateDoc();
	}

	/**
	 *
	 */
	protected function tearDown() : void {
		parent::tearDown();
		Mockery::close();
	}

	/**
	 * Loads html document.
	 *
	 * @param string|null $docRef
	 * @param string|null $name
	 * @param string|null $href
	 *
	 * @return DodoDOMDocument|null
	 */
	protected function load( ?string $docRef = null, ?string $name = null, ?string $href = null ) {
		// Replace it with actual getElementsByTagName call.
		// Use this one after Remex integration is complete.
		// $this->{'generate' . $this->snakeToPascal( $href )}();
		// $doc = $this->parseHtmlToDom( $href );
		return $this->doc;
	}

	/**
	 * @param string $href
	 *
	 * @return DodoDOMDocument|Node
	 */
	protected function parseHtmlToDom( string $href ) {
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

	/**
	 * @todo rewrite this stub
	 *
	 * @param mixed ...$arg
	 * @return null
	 */
	protected function checkInitialization( ...$arg ) {
		return null;
	}

	/**
	 * @todo rewrite this stub
	 *
	 * @return stdClass
	 */
	protected function getBuilder(): stdClass {
		$builder = new stdClass();
		$builder->contentType = 'text/html'; // could be image/svg+xml.

		return $builder;
	}
}
