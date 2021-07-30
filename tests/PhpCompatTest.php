<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong

namespace Wikimedia\Dodo\Tests;

use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DocumentFragment;

/**
 * Test the various PHP-compatibility methods added to the IDL
 * (see spec/php.webidl in the IDLeDOM repository)
 * by comparing their Dodo implementation to the corresponding
 * methods on PHP's `dom` extension (DOMDocument and friends).
 *
 * There is no standard for these methods other than "do what
 * PHP does", so that's what we'll try to check.
 */
class PhpCompatTest extends \PHPUnit\Framework\TestCase {

	/**
	 * Test compatibility with PHP's non-standard methods.
	 * @dataProvider providePhpLoadSaveAppend
	 * @covers \Wikimedia\Dodo\Document::loadXML
	 * @covers \Wikimedia\Dodo\Document::saveXML
	 * @covers \Wikimedia\Dodo\DocumentFragment::appendXML
	 */
	public function testPhpLoadSaveAppend( string $load, ?string $append = null ) {
		// First do things with PHP's native DOM
		$doc = new \DOMDocument();
		$doc->loadXML( $load );
		if ( $append !== null ) {
			$f = $doc->createDocumentFragment();
			$f->appendXML( $append );
			$doc->documentElement->appendChild( $f );
		}
		$expectedDocument = $doc->saveXML();
		$expectedElement1 = $doc->saveXML( $doc->documentElement );
		$expectedElement2 = $doc->saveXML( $doc->documentElement, LIBXML_NOEMPTYTAG );

		// Now repeat the test with Dodo's DOM
		$doc = new Document();
		$doc->loadXML( $load );
		if ( $append !== null ) {
			$f = $doc->createDocumentFragment();
			'@phan-var DocumentFragment $f'; // @var DocumentFragment $f
			$f->appendXML( $append );
			$doc->documentElement->appendChild( $f );
		}
		$actualDocument = $doc->saveXML();
		$actualElement1 = $doc->saveXML( $doc->documentElement );
		$actualElement2 = $doc->saveXML( $doc->documentElement, LIBXML_NOEMPTYTAG );

		// Okay, domino should provide identical output.
		$this->assertEquals( $expectedDocument, $actualDocument, "Serializing Document" );
		$this->assertEquals( $expectedElement1, $actualElement1, "Serializing Element" );
		$this->assertEquals( $expectedElement2, $actualElement2, "Serializing Element with NOEMPTYTAG" );
	}

	public function providePhpLoadSaveAppend() {
		return [
			// Simple load/save tests
			[ '<root><foo>text</foo><bar>text2</bar></root>' ],
			[ '<html><hr/><br/></html>' ],
			[ '<root/>', "<foo>text</foo>" ],
			// Based on Example #1 from
			// https://www.php.net/manual/en/domdocumentfragment.appendxml.php
			[ '<root/>', "<foo>text</foo><bar>text2</bar>" ],
		];
	}
}
