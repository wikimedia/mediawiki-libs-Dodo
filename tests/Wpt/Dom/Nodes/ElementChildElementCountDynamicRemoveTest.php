<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-remove.html.
class ElementChildElementCountDynamicRemoveTest extends WptTestHarness
{
    public function testElementChildElementCountDynamicRemove()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-remove.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $lec = $parentEl->lastElementChild;
            $parentEl->removeChild($lec);
            $this->assertEqualsData($parentEl->childElementCount, 1);
        });
    }
}
