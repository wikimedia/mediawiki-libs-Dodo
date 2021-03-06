<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount.html.
class ElementChildElementCountTest extends WPTTestHarness
{
    public function testElementChildElementCount()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->wptAssertTrue(isset($parentEl['childElementCount']));
            $this->wptAssertEquals($parentEl->childElementCount, 3);
        });
    }
}
