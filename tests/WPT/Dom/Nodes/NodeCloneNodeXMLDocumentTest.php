<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\IDLeDOM\XMLDocument;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-XMLDocument.html.
class NodeCloneNodeXMLDocumentTest extends WPTTestHarness
{
    public function testNodeCloneNodeXMLDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-XMLDocument.html');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createDocument('namespace', '');
            $this->wptAssertEquals($doc->constructor, XMLDocument, 'Precondition check: document.implementation.createDocument() creates an XMLDocument');
            $clone = $doc->cloneNode(true);
            $this->wptAssertEquals($clone->constructor, XMLDocument);
        }, 'Created with createDocument');
    }
}
