<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong

namespace Wikimedia\Dodo\Tests;

use RemexHtml\DOM\DOMBuilder;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;

use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\HTMLImageElement;
use Wikimedia\Dodo\NodeFilter;

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
		$doc = new Document( null, 'html' );
		$html = $doc->createElement( 'html' );
		$body = $doc->createElement( 'body' );
		$comment = $doc->createComment( 'Hello, world!' );
		$p = $doc->createElement( "p" );
		$img = new HTMLImageElement( $doc, "img" ); /* using createElement soon */
		$img->setAttribute( 'id', "foo2" );

		/* Construct the tree */
		$p->appendChild( $doc->createTextNode( 'Lorem ipsum' ) );
		$body->appendChild( $comment );
		$body->appendChild( $img );
		$body->appendChild( $p );
		$html->appendChild( $body );
		$doc->appendChild( $html );
		$img->setAttribute( 'id', "foo" );

		/* Print the tree */
		$this->assertEquals(
			'<html><body><!--Hello, world!--><img id="foo"><p>Lorem ipsum</p></body></html>',
			$doc->_node_serialize()
		);

		/* Update the attributes on the <img> node */
		$img->alt = "Incredible Vision";
		// $img->width = "1337px"; // NOTE: width stored as a string
		$img->setAttribute( 'width', '1337px' );
		$img->classList->add( 'foo' );
		$img->setAttribute( 'class', 'abc foo def' );
		$img->classList->add( 'bar' );
		$img->classList->add( 'bat' );
		$img->classList->replace( 'bat', 'foo' );

		/* Print the tree again (<img> should have attributes now) */
		$this->assertEquals(
			'<html><body><!--Hello, world!--><img id="foo" alt="Incredible Vision" width="1337px" class="abc foo def bar"><p>Lorem ipsum</p></body></html>',
			$doc->_node_serialize()
		);

		/* Print the width, the value should be an integer */
		$this->assertEquals(
			'IMG width: 0', // This doesn't work yet
			"IMG width: " . $img->width
		);

		$img3 = $doc->getElementById( 'foo' );
		$this->assertEquals( $img, $img3 );

		$img2 = $html->querySelector( "#foo" );
		$this->assertEquals( $img, $img2 );

		$this->assertTrue( true ); // success is not throwing an exception!
	}

	/** @dataProvider provideFixture */
	public function testNodeIterator1( Document $doc ) {
		$root = $doc->getElementById( 'tw' );
		$ni = $doc->createNodeIterator( $root, NodeFilter::SHOW_TEXT, function ( $n ) {
			return ( $n->data === 'ignore' ) ?
				NodeFilter::FILTER_REJECT : NodeFilter::FILTER_ACCEPT;
		} );
		$this->assertEquals( $root, $ni->root );
		$this->assertEquals( $root, $ni->referenceNode );
		$this->assertSame( 0x4, $ni->whatToShow );

		$actual = [];
		for ( $n = $ni->nextNode(); $n !== null; $n = $ni->nextNode() ) {
			$actual[] = $n;
		}
		$this->assertCount( 4, $actual );
		$this->assertEquals( $root->firstChild->firstChild, $actual[0] );
		$this->assertEquals( $root->firstChild->lastChild->firstChild, $actual[1] );
		$this->assertEquals( $root->lastChild->firstChild, $actual[2] );
		$this->assertEquals( $root->lastChild->lastChild->firstChild, $actual[3] );
	}

	public function testNodeIterator2() {
		$doc = $this->parse(
			'<a>123<b>456<script>alert(1)</script></b></a>789'
		);
		$body = null; // work around lack of $doc->getBody()
		foreach ( $doc->documentElement->childNodes ?? [] as $n ) {
			if ( $n->nodeName === 'body' ) {
				$body = $n;
			}
		}
		$ni = $doc->createNodeIterator(
			$body,
			NodeFilter::SHOW_ELEMENT | NodeFilter::SHOW_COMMENT | NodeFilter::SHOW_TEXT,
			function ( $n ) { return NodeFilter::FILTER_ACCEPT;
			}
		);
		$node = $ni->nextNode();
		$this->assertEqualsIgnoringCase( 'BODY', $node->tagName );
		$node = $ni->nextNode();
		$this->assertEqualsIgnoringCase( 'A', $node->tagName );
		$node = $ni->nextNode();
		$this->assertEquals( '#text', $node->nodeName );
		$node = $ni->nextNode();
		$this->assertEqualsIgnoringCase( 'B', $node->tagName );
		// insertAdjacentHTML is not yet implemented, so the rest is
		// commented out for now.
		/*
		'@phan-var Element $node'; // @phan-var Element $node
		$node->insertAdjacentHTML( 'AfterEnd', $node->innerHTML );
		$node->parentNode->removeChild( $node );
		$node = $ni->nextNode();
		$this->assertEquals( '#text', $node->nodeName );
		$node = $ni->nextNode();
		$this->assertEqualsIgnoringCase( 'SCRIPT', $node->tagName );
		$node->parentNode->removeChild( $node );
		$node = $ni->nextNode();
		$this->assertEquals( '#text', $node->nodeName );
		$node = $ni->nextNode();
		$this->assertNull( $node );
		$this->assertEquals( '<a>123456</a>789', $doc->body->innerHTML );
		*/
	}

	/**
	 * Parse the given HTML string into a Dodo Document.
	 * @param string $html
	 * @return Document
	 */
	private function parse( $html ) {
		$domImpl = ( new Document( null, 'html' ) )->getImplementation();
		$domBuilder = new DOMBuilder( [
			'suppressHtmlNamespace' => true,
			'suppressIdAttribute' => true,
			'domImplementation' => $domImpl,
			'domExceptionClass' => DOMException::class,
		] );
		$treeBuilder = new TreeBuilder( $domBuilder, [
			'ignoreErrors' => true
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $html, [
			'ignoreErrors' => true ]
		);
		$tokenizer->execute( [] );

		$this->assertTrue( !$domBuilder->isCoerced() );

		$result = $domBuilder->getFragment();
		$this->assertInstanceOf( Document::class, $result );
		return $result;
	}

	/** @dataProvider provideRemex */
	public function testRemex( $html, $expected ) {
		$result = $this->parse( $html );
		$this->assertEquals( $expected, $result->_node_serialize() );
	}

	public function provideRemex() {
		return [
			[
				'<p>hello</p>',
				'<html><head></head><body><p>hello</p></body></html>'
			],
			[
				'<html><body><i>Italics!',
				'<html><head></head><body><i>Italics!</i></body></html>'
			],
		];
	}

	/** @return Document */
	public function provideFixture() {
		$html = <<<HTML
<!DOCTYPE html>
<html>
<body>
  <h1 id="lorem">Lore Ipsum</h1>
  <p>
    Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Nullam quis risus eget urna mollis ornare vel eu leo. Donec <a href="https://github.com">git</a> ullamcorper nulla non metus auctor fringilla. Nulla vitae elit libero, a pharetra augue.
  </p>
  <p class="foo">
    Cras mattis <tt class="foo">consectetur</tt> purus sit amet fermentum. Donec ullamcorper nulla non metus auctor fringilla. Etiam porta sem malesuada magna mollis euismod. Duis mollis, est non commodo luctus, nisi erat porttitor <tt class="foo bar baz">ligula</tt>, eget lacinia odio sem nec elit. Donec ullamcorper nulla non metus auctor fringilla. Donec ullamcorper nulla non metus auctor fringilla.
  </p>
  <div id="tw"><div id="hello">Hello <em id="world" title="World: The Title">World</em></div>ignore<div id="foo" title="Foo: The Title">Foo, <strong id="bar">bar</strong></div></div>
</body>
</html>
HTML;
		return $this->parse( $html );
	}
}
