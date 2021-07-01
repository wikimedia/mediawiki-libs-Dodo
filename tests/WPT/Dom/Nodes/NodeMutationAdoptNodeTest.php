<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-mutation-adoptNode.html.
class NodeMutationAdoptNodeTest extends WPTTestHarness
{
    public function testNodeMutationAdoptNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-mutation-adoptNode.html');
        $this->assertTest(function () {
            $old = $this->doc->implementation->createHTMLDocument('');
            $div = $old->createElement('div');
            $div->appendChild($old->createTextNode('text'));
            $this->wptAssertEquals($div->ownerDocument, $old);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $old);
            $this->doc->body->appendChild($div);
            $this->wptAssertEquals($div->ownerDocument, $this->doc);
            $this->wptAssertEquals($div->firstChild->ownerDocument, $this->doc);
        }, 'simple append of foreign div with text');
    }
}
