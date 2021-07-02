<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-remove.html.
class ElementChildElementCountDynamicRemoveTest extends WPTTestHarness
{
    public function testElementChildElementCountDynamicRemove()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-remove.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $lec = $parentEl->lastElementChild;
            $parentEl->removeChild($lec);
            $this->wptAssertEquals($parentEl->childElementCount, 1);
        });
    }
}
