<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Document;
use Wikimedia\IDLeDOM\XMLDocument;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAnchorElement;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-constructor.html.
class DocumentConstructorTest extends WPTTestHarness
{
    public function testDocumentConstructor()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-constructor.html');
        $this->assertTest(function () {
            $doc = new Document();
            $this->wptAssertTrue($doc instanceof Node, 'Should be a Node');
            $this->wptAssertTrue($doc instanceof Document, 'Should be a Document');
            $this->wptAssertFalse($doc instanceof XMLDocument, 'Should not be an XMLDocument');
            $this->wptAssertEquals(get_class($doc), Document::class, 'Document should be the primary interface');
        }, 'new Document(): interfaces');
        $this->assertTest(function () {
            $doc = new Document();
            $this->wptAssertEquals($doc->firstChild, null, 'firstChild');
            $this->wptAssertEquals($doc->lastChild, null, 'lastChild');
            $this->wptAssertEquals($doc->doctype, null, 'doctype');
            $this->wptAssertEquals($doc->documentElement, null, 'documentElement');
            $this->wptAssertArrayEquals($doc->childNodes, [], 'childNodes');
        }, 'new Document(): children');
        $this->assertTest(function () {
            $doc = new Document();
            $this->wptAssertEquals($doc->location, null);
            $this->wptAssertEquals($doc->URL, 'about:blank');
            $this->wptAssertEquals($doc->documentURI, 'about:blank');
            $this->wptAssertEquals($doc->compatMode, 'CSS1Compat');
            $this->wptAssertEquals($doc->characterSet, 'UTF-8');
            $this->wptAssertEquals($doc->contentType, 'application/xml');
            $this->wptAssertEquals($doc->createElement('DIV')->localName, 'DIV');
            $this->wptAssertEquals($doc->createElement('a')->constructor, Element);
        }, 'new Document(): metadata');
        $this->assertTest(function () {
            $doc = new Document();
            $this->wptAssertEquals($doc->characterSet, 'UTF-8', 'characterSet');
            $this->wptAssertEquals($doc->charset, 'UTF-8', 'charset');
            $this->wptAssertEquals($doc->inputEncoding, 'UTF-8', 'inputEncoding');
        }, 'new Document(): characterSet aliases');
        $this->assertTest(function () {
            $doc = new Document();
            $a = $doc->createElementNS('http://www.w3.org/1999/xhtml', 'a');
            $this->wptAssertEquals($a->constructor, HTMLAnchorElement);
            // In UTF-8: 0xC3 0xA4
            $a->href = "http://example.org/?ä";
            $this->wptAssertEquals($a->href, 'http://example.org/?%C3%A4');
        }, 'new Document(): URL parsing');
    }
}
