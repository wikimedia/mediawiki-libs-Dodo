<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-parentNode.html.
class NodeParentNodeTest extends WptTestHarness
{
    public function testIframe($iframe)
    {
        global $t;
        $t->step(function () use(&$iframe) {
            $doc = $iframe->getOwnerDocument();
            $iframe->parentNode->removeChild($iframe);
            $this->assertEqualsData($doc->firstChild->parentNode, $doc);
        });
        $t->done();
    }
    public function testNodeParentNode()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Node-parentNode.html';
        // XXX need to test for more node types
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->parentNode, null);
        }, 'Document');
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->doctype->parentNode, $this->doc);
        }, 'Doctype');
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->documentElement->parentNode, $this->doc);
        }, 'Root element');
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $this->assertEqualsData($el->parentNode, null);
            $this->doc->body->appendChild($el);
            $this->assertEqualsData($el->parentNode, $this->doc->body);
        }, 'Element');
        $t = $this->asyncTest('Removed iframe');
    }
}
