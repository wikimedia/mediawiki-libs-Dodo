<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-add.html.
class ElementChildElementCountDynamicAddTest extends WPTTestHarness
{
    public function testElementChildElementCountDynamicAdd()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-add.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $newChild = $this->doc->createElement('span');
            $parentEl->appendChild($newChild);
            $this->assertEqualsData($parentEl->childElementCount, 2);
        });
    }
}
