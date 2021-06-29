<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-parentNode.html.
class NodeParentNodeTest extends WPTTestHarness
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
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-parentNode.html');
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
            $this->getDocBody( $this->doc )->appendChild($el);
            $this->assertEqualsData($el->parentNode, $this->getDocBody( $this->doc ));
        }, 'Element');
        $t = $this->asyncTest('Removed iframe');
    }
}
