<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-importNode.html.
class DocumentImportNodeTest extends WPTTestHarness
{
    public function testDocumentImportNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-importNode.html');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div);
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $this->wptAssertEquals($newDiv->ownerDocument, $this->doc);
            $this->wptAssertEquals($newDiv->firstChild, null);
        }, "No 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, null);
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $this->wptAssertEquals($newDiv->ownerDocument, $this->doc);
            $this->wptAssertEquals($newDiv->firstChild, null);
        }, "Undefined 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, true);
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $this->wptAssertEquals($newDiv->ownerDocument, $this->doc);
            $this->wptAssertEquals($newDiv->firstChild->ownerDocument, $this->doc);
        }, "True 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $div = $doc->body->appendChild($doc->createElement('div'));
            $div->appendChild($doc->createElement('span'));
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $newDiv = $this->doc->importNode($div, false);
            $this->wptAssertEquals($div->ownerDocument, $doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $doc);
            $this->wptAssertEquals($newDiv->ownerDocument, $this->doc);
            $this->wptAssertEquals($newDiv->firstChild, null);
        }, "False 'deep' argument.");
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('Title');
            $doc->body->setAttributeNS('http://example.com/', 'p:name', 'value');
            $originalAttr = $doc->body->getAttributeNodeNS('http://example.com/', 'name');
            $imported = $this->doc->importNode($originalAttr, true);
            $this->wptAssertEquals($imported->prefix, $originalAttr->prefix);
            $this->wptAssertEquals($imported->namespaceURI, $originalAttr->namespaceURI);
            $this->wptAssertEquals($imported->localName, $originalAttr->localName);
        }, 'Import an Attr node with namespace/prefix correctly.');
    }
}
