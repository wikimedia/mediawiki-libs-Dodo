<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong

namespace Wikimedia\Dodo\Tests;

use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\HTMLImgElement;

/**
 * @coversDefaultClass \Wikimedia\Dodo\Document
 */
class DodoTest extends \PHPUnit\Framework\TestCase {

	/**
	 * This is just a demo to show basic invocation.
	 * It's not a "real" test yet.
	 */
	public function testDodo() {
		/* Instantiate the nodes */
		$doc = new Document( 'html' );
		$html = $doc->createElement( 'html' );
		$body = $doc->createElement( 'body' );
		$comment = $doc->createComment( 'Hello, world!' );
		$p = $doc->createElement( "p" );
		$img = new HTMLImgElement( $doc, "img", "" ); /* using createElement soon */
		$img->setAttribute( 'id', "foo" );

		/* Construct the tree */
		$p->appendChild( $doc->createTextNode( 'Lorem ipsum' ) );
		$body->appendChild( $comment );
		$body->appendChild( $img );
		$body->appendChild( $p );
		$html->appendChild( $body );
		$doc->appendChild( $html );

		/* Print the tree */
		$this->assertEquals(
			'<html><body><!--Hello, world!--><img id="foo"></img><p>Lorem ipsum</p></body></html>',
			$doc->_node_serialize()
		);

		/* Update the attributes on the <img> node */
		$img->alt = "Incredible Vision";
		$img->width = "1337px"; // NOTE: width stored as a string

		/* Print the tree again (<img> should have attributes now) */
		$this->assertEquals(
			'<html><body><!--Hello, world!--><img id="foo" alt="Incredible Vision" width="1337px"></img><p>Lorem ipsum</p></body></html>',
			$doc->_node_serialize()
		);

		/* Print the width, the value should be an integer */
		$this->assertEquals(
			'IMG width: 0', // This doesn't work yet
			"IMG width: " . $img->width
		);

		$img2 = $html->querySelector( "#foo" );
		// $this->assertEquals( $img, $img2 );  // This doesn't work yet

		$this->assertTrue( true ); // success is not throwing an exception!
	}
}
