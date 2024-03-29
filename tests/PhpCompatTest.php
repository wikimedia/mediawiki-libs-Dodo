<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong

namespace Wikimedia\Dodo\Tests;

use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Text;

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
			[ '<html xmlns="http://www.w3.org/1999/xhtml"><body xmlns="http://www.w3.org/1999/xhtml"><div>4</div><!-- 5 -->6</body></html>' ],
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

	public function testPhpHtmlNamespace() {
		// Serializing documents with an HTML namespace.
		$htmlNs = "http://www.w3.org/1999/xhtml";
		$results = [ 'name' => [], 'actual' => [], 'expected' => [] ];
		'@phan-var array{name:string[],actual:string[],expected:string[]} $results';
		for ( $i = 0; $i < 2; $i++ ) {
			$which = $i == 0 ? 'expected' : 'actual';
			$addResult = static function ( string $testName, string $value ) use ( $which, &$results ) {
				$results['name'][count( $results[$which] )] = $testName;
				$results[$which][] = $value;
			};
			$impl = $i == 0 ?
				// PHP dom extension
				  new \DOMImplementation() :
				// Dodo
				  new DOMImplementation();
			$doc = $impl->createDocument(
				$htmlNs, 'html',
				$impl->createDocumentType( 'html' )
			);
			$body = $doc->createElementNS(
				$htmlNs, 'body', 'This is a weird PHP feature'
			);
			$doc->documentElement->appendChild( $body );
			// Document #1
			$addResult( "1 Document element namespace",
					   $doc->documentElement->namespaceURI ?? '<null>' );
			$addResult( "1 Body element namespace",
					   $body->namespaceURI ?? '<null>' );
			$addResult( "1 saveXML", $doc->saveXML() );
			// This result will vary, because we used createElementNS()
			#$addResult( "1 saveHTML", $doc->saveHTML() );

			// Document #2
			$doc->loadHTML( "<body>This is a weird PHP feature</body>" );
			$doc->encoding = "UTF-8";
			// This result will vary, because we used a namespace when
			// creating the Document
			#$addResult( "2 Document element namespace",
			#		   $doc->documentElement->namespaceURI ?? '<null>' );
			$addResult( "2 Body element namespace",
					   $body->namespaceURI ?? '<null>' );
			$addResult( "2 saveXML", $doc->saveXML() );
			$addResult( "2 saveHTML", $doc->saveHTML() );

			// Document #3
			$doc->loadHTML( "<html></html>" );
			$body = $doc->createElement(
				'body', 'This is a weird PHP feature'
			);
			$doc->documentElement->appendChild( $body );
			// This result will vary, because PHP assigns a null namespace
			// when ::createElement is used while spec says NS should be HTML
			#$addResult( "3 Body element namespace",
			#		   $body->namespaceURI ?? '<null>' );
			$addResult( "3 saveXML", $doc->saveXML() );
			$addResult( "3 saveHTML", $doc->saveHTML() );
		}
		$numDiff = 0;
		for ( $i = 0; $i < count( $results['name'] ); $i++ ) {
			if ( $results['expected'][$i] !== $results['actual'][$i] ) {
				$numDiff++;
				error_log( "== Difference: " . $results['name'][$i] . " ==" );
				error_log( "  Expect: " . $results['expected'][$i] );
				error_log( "  Actual: " . $results['actual'][$i] );
			}
			// Comment this out for easier human debugging (ie, see all the
			// results instead of stopping at the first failure)
			$this->assertEquals(
				$results['expected'][$i],
				$results['actual'][$i],
				$results['name'][$i]
			);
		}
		$this->assertSame( 0, $numDiff );
	}

	/**
	 * Test the offset & length units on the CharacterData APIs.
	 */
	public function testPhpCharacterData() {
		$results = [ 'name' => [], 'actual' => [], 'expected' => [] ];
		'@phan-var array{name:string[],actual:string[],expected:string[]} $results';
		for ( $i = 0; $i < 2; $i++ ) {
			$which = $i == 0 ? 'expected' : 'actual';
			$addResult = static function ( string $testName, string $value ) use ( $which, &$results ) {
				$results['name'][count( $results[$which] )] = $testName;
				$results[$which][] = $value;
			};
			$impl = $i == 0 ?
				// PHP dom extension
				  new \DOMImplementation() :
				// Dodo
				  new DOMImplementation();
			$doc = $impl->createDocument(
				"http://www.w3.org/1999/xhtml", 'html',
				$impl->createDocumentType( 'html' )
			);
			$doc->encoding = 'UTF-8';
			$body = $doc->createElement( 'body' );
			$doc->documentElement->appendChild( $body );

			$p1 = $doc->createElement( 'p', 'ASCII' );
			$p1->setAttribute( 'id', 'one-byte' );
			$body->appendChild( $p1 );

			$p2 = $doc->createElement( 'p', '¢£½߉ߝ߶' );
			$p1->setAttribute( 'id', 'two-byte' );
			$body->appendChild( $p2 );

			$p3 = $doc->createElement( 'p', 'ࠀ€—‖～' );
			$p1->setAttribute( 'id', 'three-byte' );
			$body->appendChild( $p3 );

			$p4 = $doc->createElement( 'p', '𐐷🠂😀💩' );
			$p1->setAttribute( 'id', 'four-byte' );
			$body->appendChild( $p4 );

			// Make phan happy (this is actually a DOMText in 'expected')
			$t1 = $p1->firstChild;
			'@phan-var Text $t1';
			$t2 = $p2->firstChild;
			'@phan-var Text $t2';
			$t3 = $p3->firstChild;
			'@phan-var Text $t3';
			$t4 = $p4->firstChild;
			'@phan-var Text $t4';

			$addResult( '1 byte length', strval( $t1->length ) );
			$addResult( '2 byte length', strval( $t2->length ) );
			$addResult( '3 byte length', strval( $t3->length ) );
			$addResult( '4 byte length', strval( $t4->length ) );

			$addResult( '1 byte substr', $t1->substringData( 1, 1 ) );
			$addResult( '2 byte substr', $t2->substringData( 1, 1 ) );
			$addResult( '3 byte substr', $t3->substringData( 1, 1 ) );
			$addResult( '4 byte substr', $t4->substringData( 1, 1 ) );

			$t1->insertData( 1, ' ' );
			$t2->insertData( 1, ' ' );
			$t3->insertData( 1, ' ' );
			$t4->insertData( 1, ' ' );
			$addResult( 'insertData', $doc->saveHTML( $body ) );

			$t1->deleteData( 2, 1 );
			$t2->deleteData( 2, 1 );
			$t3->deleteData( 2, 1 );
			$t4->deleteData( 2, 1 );
			$addResult( 'deleteData', $doc->saveHTML( $body ) );

			$t1->replaceData( 0, 2, '!' );
			$t2->replaceData( 0, 2, '!' );
			$t3->replaceData( 0, 2, '!' );
			$t4->replaceData( 0, 2, '!' );
			$addResult( 'replaceData', $doc->saveHTML( $body ) );

			$t1->splitText( 2 );
			$t2->splitText( 2 );
			$t3->splitText( 2 );
			$t4->splitText( 2 );
			$addResult( 'splitText', $doc->saveHTML( $body ) );
		}
		$numDiff = 0;
		for ( $i = 0; $i < count( $results['name'] ); $i++ ) {
			if ( $results['expected'][$i] !== $results['actual'][$i] ) {
				$numDiff++;
				error_log( "== Difference: " . $results['name'][$i] . " ==" );
				error_log( "  Expect: " . $results['expected'][$i] );
				error_log( "  Actual: " . $results['actual'][$i] );
			}
			// Comment this out for easier human debugging (ie, see all the
			// results instead of stopping at the first failure)
			$this->assertEquals(
				$results['expected'][$i],
				$results['actual'][$i],
				$results['name'][$i]
			);
		}
		$this->assertSame( 0, $numDiff );
	}
}
