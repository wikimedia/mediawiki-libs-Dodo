<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-nochild.html.
class ElementChildElementCountNochildTest extends WPTTestHarness
{
    public function testElementChildElementCountNochild()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-nochild.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->wptAssertEquals($parentEl->childElementCount, 0);
        });
    }
}
