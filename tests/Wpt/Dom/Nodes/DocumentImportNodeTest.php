<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-importNode.html.
class DocumentImportNodeTest extends WptTestHarness
{
    public function testDocumentImportNode()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-importNode.html');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div);
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $this->assertEqualsData($newDiv->ownerDocument, $this->doc);
            $this->assertEqualsData($newDiv->firstChild, null);
        }, "No 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, null);
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $this->assertEqualsData($newDiv->ownerDocument, $this->doc);
            $this->assertEqualsData($newDiv->firstChild, null);
        }, "Undefined 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, true);
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $this->assertEqualsData($newDiv->ownerDocument, $this->doc);
            $this->assertEqualsData($newDiv->firstChild->ownerDocument, $this->doc);
        }, "True 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, false);
            $this->assertEqualsData($div->ownerDocument, $doc);
            $this->assertEqualsData($div->firstChild->ownerDocument, $doc);
            $this->assertEqualsData($newDiv->ownerDocument, $this->doc);
            $this->assertEqualsData($newDiv->firstChild, null);
        }, "False 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $doc->body->setAttributeNS('http://example.com/', 'p:name', 'value');
            $originalAttr = $doc->body->getAttributeNodeNS('http://example.com/', 'name');
            $imported = $this->doc->importNode($originalAttr, true);
            $this->assertEqualsData($imported->prefix, $originalAttr->prefix);
            $this->assertEqualsData($imported->namespaceURI, $originalAttr->namespaceURI);
            $this->assertEqualsData($imported->localName, $originalAttr->localName);
        }, 'Import an Attr node with namespace/prefix correctly.');
    }
}
