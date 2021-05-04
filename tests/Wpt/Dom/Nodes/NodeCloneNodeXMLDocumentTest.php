<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\IDLeDOM\XMLDocument;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-XMLDocument.html.
class NodeCloneNodeXMLDocumentTest extends WptTestHarness
{
    public function testNodeCloneNodeXMLDocument()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-XMLDocument.html');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createDocument('namespace', '');
            $this->assertEqualsData($doc->constructor, XMLDocument, 'Precondition check: document.implementation.createDocument() creates an XMLDocument');
            $clone = $doc->cloneNode(true);
            $this->assertEqualsData($clone->constructor, XMLDocument);
        }, 'Created with createDocument');
    }
}
