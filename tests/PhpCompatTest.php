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
			[ '<html><!--foo--><hr/><br/><!--bar--></html>' ],
			[ '<root/>', "<foo>text</foo>" ],
			// Based on Example #1 from
			// https://www.php.net/manual/en/domdocumentfragment.appendxml.php
			[ '<root/>', "<foo>text</foo><bar>text2</bar>" ],
		];
	}

	/**
	 * Test compatibility with PHP's non-standard methods.
	 * @dataProvider providePhpLoadSaveHtml
	 * @covers \Wikimedia\Dodo\Document::loadHTML
	 * @covers \Wikimedia\Dodo\Document::saveHTML
	 */
	public function testPhpLoadSaveHtml( string $load, $options = 0 ) {
		// First do things with PHP's native DOM
		$doc = new \DOMDocument();
		$doc->appendChild( $doc->createElement( 'p' ) );
		$doc->loadHTML( $load, $options );
		$expectedDocument = $doc->saveHTML();
		$expectedElement = $doc->saveHTML( $doc->documentElement );
		$expectedHasDoctype = ( $doc->doctype !== null );
		$expectedHasHead = count( $doc->getElementsByTagName( 'head' ) );
		$expectedHasBody = count( $doc->getElementsByTagName( 'body' ) );

		// Now repeat the test with Dodo's DOM
		$doc = new Document();
		$doc->appendChild( $doc->createElement( 'p' ) );
		$doc->loadHTML( $load, $options );
		$actualDocument = $doc->saveHTML();
		$actualElement = $doc->saveHTML( $doc->documentElement );
		$actualHasDoctype = ( $doc->doctype !== null );
		$actualHasHead = count( $doc->getElementsByTagName( 'head' ) );
		$actualHasBody = count( $doc->getElementsByTagName( 'body' ) );

		// Verify that trees are similar
		$this->assertEquals( $expectedHasDoctype, $actualHasDoctype, "has doctype" );
		$this->assertEquals( $expectedHasHead, $actualHasHead, "has <head>" );
		$this->assertEquals( $expectedHasBody, $actualHasBody, "has <body>" );
		if ( $expectedHasHead > 0 ) {
			$this->assertNotNull( $doc->getHead(), "head not null" );
		}
		if ( $expectedHasBody > 0 ) {
			$this->assertNotNull( $doc->getBody(), "body not null" );
		}

		// Okay, domino should provide identical output.
		$this->assertEquals( $expectedDocument, $actualDocument, "Serializing Document" );
		$this->assertEquals( $expectedElement, $actualElement, "Serializing Element" );
	}

	public function providePhpLoadSaveHtml() {
		return [
			// Simple load/save tests
			[ '<html><hr/><br/></html>' ],
			[ '<html><head><title>This is the title' ],
			[ '<!DOCTYPE html><p>foo' ],
			[ '<html><head></head></html>' ],
			[ '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"><p>bar' ],
			[ '<html><body></body></html>' ],
		];
	}
}
