<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong

namespace Wikimedia\Dodo\Tests;

use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLBodyElement;
use Wikimedia\Dodo\HTMLCollection;
use Wikimedia\Dodo\HTMLImageElement;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\XMLDocument;
use Wikimedia\Dodo\XMLSerializer;

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
		$doc = new Document();
		// @phan-suppress-next-line PhanAccessMethodInternal
		$doc->_setContentType( 'text/html', true );

		$all_elements = $doc->getElementsByTagName( '*' );
		$this->assertSame( 0, $all_elements->length );

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

		$this->assertEquals( $body, $doc->getBody() );

		/* Print the tree */
		$this->assertEquals(
			'<body><!--Hello, world!--><img id="foo"><p>Lorem ipsum</p></body>',
			$doc->body->outerHTML
		);

		/* Update the attributes on the <img> node */
		$img->alt = "Incredible Vision";
		// $img->width = "1337px"; // NOTE: width stored as a string
		$img->setAttribute( 'width', '1337px' );
		$img->classList->add( 'foo' );
		$img->setAttribute( 'class', 'abc foo def' );
		$img->setWidth( 100 );
		$img->setHeight( 200 );
		$img->classList->add( 'bar' );
		$img->classList->add( 'bat' );
		$img->classList->replace( 'bat', 'foo' );

		$this->assertEquals( 100, $img->getWidth() );
		$this->assertEquals( 200, $img->getHeight() );

		/* Print the tree again (<img> should have attributes now) */
		$result = [];
		$doc->_htmlSerialize( $result );
		$this->assertEquals(
			'<html><body><!--Hello, world!--><img id="foo" alt="Incredible Vision" width="100" class="abc foo def bar" height="200"><p>Lorem ipsum</p></body></html>',
			implode( '', $result )
		);

		$img3 = $doc->getElementById( 'foo' );
		$this->assertEquals( $img, $img3 );

		$img2 = $html->querySelector( "#foo" );
		$this->assertEquals( $img, $img2 );

		$this->assertTrue( true ); // success is not throwing an exception!

		/* Test getElementsByTagName  */
		$p_tags = $doc->getElementsByTagName( 'p' );
		$this->assertNotNull( $p_tags );
		$this->assertInstanceOf( HTMLCollection::class, $p_tags );
		$this->assertSame( 1, $p_tags->length );

		$first_p_tag = $p_tags->item( 0 );
		$this->assertNotNull( $first_p_tag );
		$this->assertInstanceOf( Element::class, $first_p_tag );
		$this->assertEqualsIgnoringCase( 'P', $first_p_tag->tagName );

		$second_p_tag = $p_tags->item( 1 );
		$this->assertNull( $second_p_tag );

		/* Test getElementsByTagNameNS  */
		$p_tags_ns = $doc->getElementsByTagNameNS( '*', 'p' );
		$this->assertNotNull( $p_tags_ns );
		$this->assertInstanceOf( HTMLCollection::class, $p_tags_ns );
		$this->assertSame( 1, $p_tags_ns->length );

		$first_p_tag = $p_tags_ns->item( 0 );
		$this->assertNotNull( $first_p_tag );
		$this->assertInstanceOf( Element::class, $first_p_tag );
		$this->assertEqualsIgnoringCase( 'P', $first_p_tag->tagName );

		$second_p_tag = $p_tags_ns->item( 1 );
		$this->assertNull( $second_p_tag );

		// Test liveness!
		$body->appendChild( $doc->createElement( "p" ) );
		$this->assertSame( 5, $all_elements->length );

		$this->assertSame( 2, $p_tags->length );
		$second_p_tag = $p_tags->item( 1 );
		$this->assertNotNull( $second_p_tag );
		$this->assertInstanceOf( Element::class, $second_p_tag );
		$this->assertEqualsIgnoringCase( 'P', $second_p_tag->tagName );

		$this->assertSame( 2, $p_tags_ns->length );
		$second_p_tag = $p_tags_ns->item( 1 );
		$this->assertNotNull( $second_p_tag );
		$this->assertInstanceOf( Element::class, $second_p_tag );
		$this->assertEqualsIgnoringCase( 'P', $second_p_tag->tagName );

		// Test getElementsByClass name
		$els_by_class = $doc->getElementsByClassName( 'abc foo def' );
		$this->assertNotNull( $els_by_class );
		$this->assertInstanceOf( HTMLCollection::class, $els_by_class );
		$this->assertSame( 1, $els_by_class->length );

		$first_el = $els_by_class->item( 0 );
		$this->assertInstanceOf( HTMLImageElement::class, $first_el );
		$this->assertEqualsIgnoringCase( 'IMG', $first_el->tagName );

		// documentElement should match too
		$doc->documentElement->setAttribute( 'class', "test1\ttest2\t" );
		$els_by_class = $doc->getElementsByClassName( 'test2' );
		$this->assertNotNull( $els_by_class );
		$this->assertInstanceOf( HTMLCollection::class, $els_by_class );
		$this->assertSame( 1, $els_by_class->length );
		$this->assertSame( $doc->documentElement, $els_by_class[0] );
	}

	/** @dataProvider provideFixture */
	public function testNodeIterator1( Document $doc ) {
		$root = $doc->getElementById( 'tw' );
		$ni = $doc->createNodeIterator( $root, NodeFilter::SHOW_TEXT, static function ( $n ) {
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
		$this->assertNotNull( $doc->getDocumentElement() );
		$body = $doc->getBody();
		$this->assertTrue( $body !== null );
		'@phan-var HTMLBodyElement $body'; // @phan-var HTMLBodyElement $body

		$ni = $doc->createNodeIterator(
			$body,
			NodeFilter::SHOW_ELEMENT | NodeFilter::SHOW_COMMENT | NodeFilter::SHOW_TEXT,
			static function ( $n ) {
				return NodeFilter::FILTER_ACCEPT;
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

	public function testDocumentFragmentGetElementById() {
		$doc = $this->parse( '<p id=x>test<span id=x>' );
		$p1 = $doc->querySelector( 'p' );
		$this->assertNotNull( $p1 );
		'@phan-var Node $p1'; // @phan-var Node $p1
		$p2 = $doc->getElementById( 'x' );
		$this->assertSame( $p1, $p2 );
		$docFrag = $doc->createDocumentFragment();
		$docFrag->appendChild( $p1 );
		$p3 = $docFrag->getElementById( 'x' );
		$this->assertSame( $p1, $p3 );
	}

	/**
	 * Parse the given HTML string into a Dodo Document.
	 * @param string $html
	 * @return Document
	 */
	private function parse( $html ) {
		$parser = new DOMParser();
		return $parser->parseFromString( $html, "text/html" );
	}

	/** @dataProvider provideHtml */
	public function testDOMParser( $html, $expected ) {
		$node = $this->parse( $html );
		$result = [];
		$node->_htmlSerialize( $result );
		$this->assertEquals( $expected, implode( '', $result ) );
	}

	public function provideHtml() {
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

	public function testXmlSerialization() {
		// Since we don't have an XML parser yet, manually construct an
		// XML document for these tests
		$doc = new XMLDocument( null, 'text/xml' );
		$root = $doc->createElement( 'root' );
		$doc->appendChild( $root );
		$child1 = $doc->createElement( 'child1' );
		$root->appendChild( $child1 );
		$text = $doc->createTextNode( 'value1' );
		$child1->appendChild( $text );

		/*
		$child2 = $doc->createElementNS( Util::NAMESPACE_SVG, 'svg' );
		$root->appendChild($child2);
		*/

		$this->assertEquals(
			'<root><child1>value1</child1></root>',
			$root->outerHTML
		);
		$this->assertEquals(
			'<?xml version="1.0" encoding="UTF-8"?>' .
			'<root><child1>value1</child1></root>',
			( new XMLSerializer() )->serializeToString( $doc )
		);
	}

	public function testXmlParse() {
		$doc = ( new DOMParser() )->parseFromString(
			"<!DOCTYPE html><html><body><pre>\n</pre></body></html>",
			"text/xml"
		);
		$this->assertEquals(
			'<?xml version="1.0" encoding="UTF-8"?>' .
			"<!DOCTYPE html><html><body><pre>\n</pre></body></html>",
			( new XMLSerializer() )->serializeToString( $doc )
		);
	}

	public function testXmlParse2() {
		$doc = ( new DOMParser() )->parseFromString(
			'<!DOCTYPE html><html><body></body></html>',
			"text/html"
		);
		$this->assertEquals(
			'<?xml version="1.0" encoding="UTF-8"?>' .
			"<!DOCTYPE html><html xmlns=\"http://www.w3.org/1999/xhtml\"><head></head><body></body></html>",
			( new XMLSerializer() )->serializeToString( $doc )
		);
	}

}
