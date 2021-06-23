<?php

namespace Wikimedia\Dodo\Tests\W3c\Harness;

use Mockery;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Finder\Finder;
use Wikimedia\Dodo\Document as DodoDOMDocument;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Node as DOMNode;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;

/**
 * W3cTestHarness.php
 * --------
 * Derived from DomTestCase.js
 *
 * @see vendor/fgnass/domino/test/w3c/harness/DomTestCase.js
 * @package Wikimedia\Dodo\Tests
 */
abstract class W3cTestHarness extends TestCase {
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
		self::assertNull( $actual,
			$message );
	}

	/**
	 *
	 * @param string $message
	 * @param string $actual
	 */
	public function assertFalseData( string $message, string $actual ) : void {
		self::assertFalse( !$actual,
			$message );
	}

	/**
	 * @param string|null $message
	 * @param bool|null $actual
	 */
	public function assertTrueData( ?string $message, ?bool $actual ) : void {
		self::assertTrue( $actual,
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
		self::assertNotEquals( null,
			$actual,
			$message );
	}

	/**
	 * @param string|null $message
	 * @param string|null $expected
	 * @param string|null $actual
	 */
	public function assertEqualsData( ?string $message, ?string $expected, ?string $actual ) : void {
		self::assertEquals( $expected,
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
		Assert::assertCount( count( $expected ), $actual, $descr );

		// if their length is the same, then every entry in the expected list
		// must appear once and only once in the actual list
		$expectedValue = null;
		$i = null;
		$j = null;
		$matches = null;
		foreach ( $expected as $iValue ) {
			$matches = 0;
			$expectedValue = $iValue;
			foreach ( $actual as $jValue ) {
				if ( $this->contentType === 'text/html' ) {
					if ( $context === 'attribute' ) {
						if ( strtolower( $expectedValue ) == strtolower( $jValue ) ) {
							$matches++;
						}
					} else {

						if ( strtoupper( $expectedValue ) == $jValue ) {
							$matches++;
						}
					}
				} else {
					if ( $expectedValue == $jValue ) {
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
	 * @param array $expected
	 * @param array $actual
	 */
	public function assertEqualsCollectionData( string $descr, array $expected, array $actual ) : void {
		Assert::assertEquals( $expected, $actual, $descr );
	}

	/**
	 * @param string $context
	 * @param string $descr
	 * @param array $expected
	 * @param array $actual
	 */
	public function assertEqualsListAutoCaseData( string $context, string $descr, array $expected, array $actual ) : void {
		$minLength = count( $expected );
		if ( count( $actual ) < $minLength ) {
			$minLength = count( $actual );
		}
		for ( $i = 0; $i < $minLength; $i++ ) {
			$this->assertEqualsAutoCaseData( $context,
				$descr,
				$expected[$i],
				$actual[$i] );
		}
		Assert::assertCount( count( $expected ), $actual, $descr );
	}

	/**
	 * @param string|null $context
	 * @param string|null $descr
	 * @param string|null $expected
	 * @param string|null $actual
	 */
	public function assertEqualsAutoCaseData( ?string $context, ?string $descr, ?string $expected, ?string $actual ) : void {
		if ( $this->contentType === 'text/html' ) {
			if ( $context === 'attribute' ) {
				Assert::assertEqualsIgnoringCase( $expected, $actual, $descr );
			} else {
				Assert::assertEquals( strtoupper( $expected ), $actual, $descr );
			}
		} else {
			Assert::assertEquals( $expected, $actual, $descr );
		}
	}

	/**
	 * @param string|null $descr
	 * @param array $expected
	 * @param array $actual
	 */
	public function assertEqualsListData( ?string $descr, array $expected, $actual ) : void {
		$minLength = count( $expected );
		if ( count( $actual ) < $minLength ) {
			$minLength = count( $actual );
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
			count( $expected ),
			count( $actual ) );
	}

	/**
	 * @param string $descr
	 * @param string $type
	 * @param string $obj
	 */
	public function assertInstanceOfData( string $descr, string $type, string $obj ) : void {
		if ( $type === 'Attr' ) {
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
	public function assertSameData( string $descr, Node $expected, Node $actual ) : void {
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
	 * @param string|null $scheme
	 * @param string|null $path
	 * @param string|null $host
	 * @param string|null $file
	 * @param string|null $name
	 * @param string|null $query
	 * @param string|null $fragment
	 * @param string|null $isAbsolute
	 * @param string|null $actual
	 */
	public function assertURIEqualsData( string $assertID, ?string $scheme, ?string $path, ?string $host,
		?string $file, ?string $name, ?string $query, ?string $fragment, ?string $isAbsolute, ?string $actual ) : void {
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
					2/*CHECK THIS*/ ) === '//' ) {
				// TODO Test this!.
				$termSlash = strpos( substr( $actualPath,
						2 ),
						'/' ) + 2;
				$actualHost = substr( $actualPath,
					0,
					$termSlash/*CHECK THIS*/ );
			}
			$this->assertEqualsData( $assertID,
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
					1/*CHECK THIS*/ ) === '/' );
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

	/**
	 * @param string $error
	 *
	 * @throws DOMException
	 */
	public function makeFailed( string $error ) {
		if ( $error === 'throw_HIER_OR_NO_MOD_ERR' ) {
			throw new DOMException( 'throw_HIER_OR_NO_MOD_ERR',
				'NoModificationAllowedError' );
		}
	}

	/** @inheritDoc */
	protected function tearDown() : void {
		parent::tearDown();
		Mockery::close();
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

	/**
	 * @return DOMImplementation
	 */
	protected function getImplementation() : DOMImplementation {
		return $this->doc->getImplementation();
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
		$this->contentType = 'text/html';
		$realpath = realpath( '.' );
		$file_path = iterator_to_array( ( new Finder() )->name( $href . '.html' )->in( realpath( '.' ) . '/tests/W3c' )
			->files()->sortByName() );

		return $this->parseHtmlToDom( array_key_first( $file_path ) );
	}
}
