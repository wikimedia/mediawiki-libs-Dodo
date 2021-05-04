<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Document;
use Wikimedia\IDLeDOM\XMLDocument;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAnchorElement;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-constructor.html.
class DocumentConstructorTest extends WptTestHarness
{
    public function testDocumentConstructor()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-constructor.html');
        $this->assertTest(function () {
            $doc = new Document();
            $this->assertTrueData($doc instanceof Node, 'Should be a Node');
            $this->assertTrueData($doc instanceof Document, 'Should be a Document');
            $this->assertFalseData($doc instanceof XMLDocument, 'Should not be an XMLDocument');
            $this->assertEqualsData(get_class($doc), Document::class, 'Document should be the primary interface');
        }, 'new Document(): interfaces');
        $this->assertTest(function () {
            $doc = new Document();
            $this->assertEqualsData($doc->firstChild, null, 'firstChild');
            $this->assertEqualsData($doc->lastChild, null, 'lastChild');
            $this->assertEqualsData($doc->doctype, null, 'doctype');
            $this->assertEqualsData($doc->documentElement, null, 'documentElement');
            $this->assertArrayEqualsData($doc->childNodes, [], 'childNodes');
        }, 'new Document(): children');
        $this->assertTest(function () {
            $doc = new Document();
            $this->assertEqualsData($doc->location, null);
            $this->assertEqualsData($doc->URL, 'about:blank');
            $this->assertEqualsData($doc->documentURI, 'about:blank');
            $this->assertEqualsData($doc->compatMode, 'CSS1Compat');
            $this->assertEqualsData($doc->characterSet, 'UTF-8');
            $this->assertEqualsData($doc->contentType, 'application/xml');
            $this->assertEqualsData($doc->createElement('DIV')->localName, 'DIV');
            $this->assertEqualsData($doc->createElement('a')->constructor, Element);
        }, 'new Document(): metadata');
        $this->assertTest(function () {
            $doc = new Document();
            $this->assertEqualsData($doc->characterSet, 'UTF-8', 'characterSet');
            $this->assertEqualsData($doc->charset, 'UTF-8', 'charset');
            $this->assertEqualsData($doc->inputEncoding, 'UTF-8', 'inputEncoding');
        }, 'new Document(): characterSet aliases');
        $this->assertTest(function () {
            $doc = new Document();
            $a = $doc->createElementNS('http://www.w3.org/1999/xhtml', 'a');
            $this->assertEqualsData($a->constructor, HTMLAnchorElement);
            // In UTF-8: 0xC3 0xA4
            $a->href = "http://example.org/?ä";
            $this->assertEqualsData($a->href, 'http://example.org/?%C3%A4');
        }, 'new Document(): URL parsing');
    }
}
