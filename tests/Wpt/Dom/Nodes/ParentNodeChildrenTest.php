<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-children.html.
class ParentNodeChildrenTest extends WptTestHarness
{
    public function testParentNodeChildren()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-children.html');
        $this->assertTest(function () {
            $node = $this->doc->getElementById('test');
            $parentNode = $node->parentNode;
            $children = $parentNode->children;
            $this->assertTrueData($children instanceof HTMLCollection);
            $li = $this->doc->createElement('li');
            $this->assertEqualsData(count($children), 4);
            $parentNode->appendChild($li);
            $this->assertEqualsData(count($children), 5);
            $parentNode->removeChild($li);
            $this->assertEqualsData(count($children), 4);
        }, 'ParentNode.children should be a live collection');
    }
}
