<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Tests\Harness;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Finder\Finder;
use Wikimedia\Dodo\Document as DodoDOMDocument;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;
use Wikimedia\IDLeDOM\Document;
use Wikimedia\IDLeDOM\DOMImplementation;
use Wikimedia\IDLeDOM\Node;

/**
 * W3CTestHarness
 *
 * Derived from DomTestCase.js
 *
 * @see vendor/fgnass/domino/test/w3c/harness/index.js
 * @see vendor/fgnass/domino/test/w3c/harness/DomTestCase.js
 */
abstract class W3CTestHarness extends TestCase {
	use Helpers;

	/**
	 * @var Document
	 */
	protected $doc;

	/**
	 * @var string
	 */
	protected $contentType;

	// The following assertion methods come from
	// vendor/fgnass/domino/test/w3c/harness/index.js

	/**
	 * @param string $message
	 * @param mixed $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEquals( string $message, $expected, $actual ): void {
		Assert::assertEquals( $expected, $actual, $message );
	}

	/**
	 * @param string $message
	 * @param bool $actual
	 */
	public function w3cAssertTrue( string $message, bool $actual ): void {
		Assert::assertTrue( $actual, $message );
	}

	/**
	 *
	 * @param string $message
	 * @param bool $actual
	 */
	public function w3cAssertFalse( string $message, bool $actual ): void {
		Assert::assertFalse( $actual, $message );
	}

	/**
	 * @param string $message
	 * @param mixed $actual
	 */
	public function w3cAssertNull( string $message, $actual ): void {
		if ( is_object( $actual ) ) {
			// Make the exception message cleaner
			$actual = "[object " . get_class( $actual ) . "]";
		}
		Assert::assertNull( $actual, $message );
	}

	/**
	 *
	 * @param string $message
	 * @param mixed $actual
	 */
	public function w3cAssertNotNull( string $message, $actual ): void {
		Assert::assertNotNull( $actual, $message );
	}

	// The following assertion methods come from
	// vendor/fgnass/domino/test/w3c/harness/DomTestCase.js

	/**
	 * @param string $descr
	 * @param int $expected
	 * @param mixed $actual
	 */
	public function w3cAssertSize( string $descr, int $expected, $actual ): void {
		Assert::assertCount( $expected, $actual, $descr );
	}

	/**
	 * @param string $context
	 * @param string $descr
	 * @param string $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEqualsAutoCase(
		string $context, string $descr, string $expected, $actual
	): void {
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
	 * @param string $context
	 * @param string $descr
	 * @param array $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEqualsCollectionAutoCase(
		string $context, string $descr, array $expected, $actual
	): void {
		// This version of the assertion doesn't care about item order
		if ( $this->contentType !== 'text/html' ) {
			Assert::assertEqualsCanonicalizing( $expected, $actual, $descr );
		} elseif ( $context === 'attribute' ) {
			// The following code works, but *only on PHPUnit 9* (which
			// requires PHP 7.3).
			// https://github.com/sebastianbergmann/phpunit/issues/4724
			// Once we drop PHP 7.2 / PHPUnit 8 support we can
			// uncomment this implementation:
			/*
			Assert::assertThat(
				$actual,
				// canonicalizing *and* ignore case
				new \PHPUnit\Framework\Constraint\IsEqual( $expected, 0.0, true, true ),
				$descr
			);
			*/
			// XXX But in practice this assertion isn't currently used by
			// any test, so it's safe to just fail...
			throw new \Exception( "unimplemented" );
		} else {
			// convert expected to upper case, then canonicalizing.
			$ne = [];
			foreach ( $expected as $val ) {
				$ne[] = strtoupper( $val );
			}
			Assert::assertEqualsCanonicalizing( $ne, $actual, $descr );
		}
	}

	/**
	 * @param string $descr
	 * @param array $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEqualsCollection(
		string $descr, array $expected, $actual
	): void {
		// This version of the assertion doesn't care about item order
		Assert::assertEqualsCanonicalizing( $expected, $actual, $descr );
	}

	/**
	 * @param string $context
	 * @param string $descr
	 * @param array $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEqualsListAutoCase(
		string $context, string $descr, array $expected, $actual
	): void {
		// This version of the assertion requires the items to match in order
		if ( $this->contentType !== 'text/html' ) {
			Assert::assertEquals( $expected, $actual, $descr );
		} elseif ( $context === 'attribute' ) {
			Assert::assertEqualsIgnoringCase( $expected, $actual, $descr );
		} else {
			// convert expected to upper case, then compare
			$ne = [];
			foreach ( $expected as $val ) {
				$ne[] = strtoupper( $val );
			}
			Assert::assertEquals( $ne, $actual, $descr );
		}
	}

	/**
	 * @param string $descr
	 * @param array $expected
	 * @param mixed $actual
	 */
	public function w3cAssertEqualsList(
		string $descr, array $expected, $actual
	): void {
		// This version of the assertion requires the items to match in order
		Assert::assertEquals( $expected, $actual, $descr );
	}

	// assertInstanceOf is unused

	/**
	 * @param string $descr
	 * @param Node $expected
	 * @param Node $actual
	 */
	public function w3cAssertSame(
		string $descr, Node $expected, Node $actual
	): void {
		if ( $actual === $expected ) {
			// This should always succeed, but we need to make sure our
			// framework knows an assertion test was performed.
			Assert::assertSame( $expected, $actual, $descr );
		} else {
			Assert::assertEquals(
				$expected->nodeType,
				$actual->nodeType,
				$descr
			);
			Assert::assertEquals(
				$expected->nodeValue,
				$actual->nodeValue,
				$descr
			);
		}
	}

	/**
	 * @param string $descr
	 * @param string|null $scheme
	 * @param string|null $path
	 * @param string|null $host
	 * @param string|null $file
	 * @param string|null $name
	 * @param string|null $query
	 * @param string|null $fragment
	 * @param bool|null $isAbsolute
	 * @param string|null $actual
	 */
	public function w3cAssertURIEquals(
		string $descr, ?string $scheme, ?string $path, ?string $host,
		?string $file, ?string $name, ?string $query, ?string $fragment,
		?bool $isAbsolute, $actual
	): void {
		//
		// URI must be non-null
		Assert::assertNotNull( $actual, $descr );
		'@phan-var string $actual';

		$uri = $actual;

		$lastPound = strrpos( $actual, '#' );
		$actualFragment = '';
		if ( $lastPound !== false ) {
			//
			//  substring before pound
			//
			$uri = substr( $actual, 0, $lastPound );
			$actualFragment = substr( $actual, $lastPound + 1 );
		}
		if ( $fragment !== null ) {
			Assert::assertEquals( $fragment, $actualFragment, $descr );
		}

		$lastQuestion = strrpos( $uri, '?' );
		$actualQuery = '';
		if ( $lastQuestion !== false ) {
			//
			//  substring before query
			//
			$uri = substr( $actual, 0, $lastQuestion );
			$actualQuery = substr( $actual, $lastQuestion + 1 );
		}
		if ( $query !== null ) {
			Assert::assertEquals( $query, $actualQuery, $descr );
		}

		$firstColon = strpos( $uri, ':' );
		$firstSlash = strpos( $uri, '/' );
		$actualPath = $uri;
		$actualScheme = '';
		if (
			$firstColon !== false &&
			( $firstSlash === false || $firstColon < $firstSlash )
		) {
			$actualScheme = substr( $uri, 0, $firstColon );
			$actualPath = substr( $uri, $firstColon + 1 );
		}

		if ( $scheme !== null ) {
			Assert::assertEquals( $scheme, $actualScheme, $descr );
		}

		if ( $path !== null ) {
			Assert::assertEquals( $path, $actualPath, $descr );
		}

		if ( $host !== null ) {
			$actualHost = '';
			if ( substr( $actualPath, 0, 2 ) === '//' ) {
				$termSlash = strpos( $actualPath, '/', 2 );
				$actualHost = ( $termSlash === false ) ? $actualPath :
					substr( $actualPath, 0, $termSlash );
			}
			Assert::assertEquals( $host, $actualHost, $descr );
		}

		if ( $file !== null || $name !== null ) {
			$actualFile = $actualPath;
			$finalSlash = strrpos( $actualPath, '/' );
			if ( $finalSlash !== false ) {
				$actualFile = substr( $actualPath, $finalSlash + 1 );
			}
			if ( $file !== null ) {
				Assert::assertEquals( $file, $actualFile, $descr );
			}
			if ( $name != null ) {
				$actualName = $actualFile;
				$finalDot = strrpos( $actualFile, '.' );
				if ( $finalDot !== false ) {
					$actualName = substr( $actualName, 0, $finalDot );
				}
				Assert::assertEquals( $name, $actualName, $descr );
			}
		}

		if ( $isAbsolute !== null ) {
			Assert::assertEquals(
				$isAbsolute,
				substr( $actualPath, 0, 1 ) === '/',
				$descr . ' ' . $actualPath
			);
		}
	}

	/**
	 * @param string $error
	 */
	public function w3cFail( string $error ) {
		Assert::fail( $error );
	}

	/** @inheritDoc */
	protected function tearDown(): void {
		parent::tearDown();
	}

	/**
	 * @todo rewrite this stub
	 *
	 * @param mixed $builder
	 * @param string $testName
	 * @return null
	 */
	protected function checkInitialization( $builder, string $testName ) {
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
	protected function getImplementation(): DOMImplementation {
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
	protected function load( $docRef = null, ?string $name = null, ?string $href = null ): ?Node {
		$this->contentType = 'text/html';
		$realpath = realpath( '.' );
		$file_path = iterator_to_array( ( new Finder() )->name( $href . '.html' )->in( realpath( '.' ) . '/tests/W3C' )
			->files()->sortByName() );

		// @phan-suppress-next-line PhanTypeMismatchReturn
		return $this->parseHtmlToDom( array_key_first( $file_path ) );
	}
}
