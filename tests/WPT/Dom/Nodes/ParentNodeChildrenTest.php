<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-children.html.
class ParentNodeChildrenTest extends WPTTestHarness
{
    public function testParentNodeChildren()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-children.html');
        $this->assertTest(function () {
            $node = $this->doc->getElementById('test');
            $parentNode = $node->parentNode;
            $children = $parentNode->children;
            $this->wptAssertTrue($children instanceof HTMLCollection);
            $li = $this->doc->createElement('li');
            $this->wptAssertEquals(count($children), 4);
            $parentNode->appendChild($li);
            $this->wptAssertEquals(count($children), 5);
            $parentNode->removeChild($li);
            $this->wptAssertEquals(count($children), 4);
        }, 'ParentNode.children should be a live collection');
    }
}
