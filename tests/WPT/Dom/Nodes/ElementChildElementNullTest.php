<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElement-null.html.
class ElementChildElementNullTest extends WPTTestHarness
{
    public function testElementChildElementNull()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElement-null.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->wptAssertEquals($parentEl->firstElementChild, null);
            $this->wptAssertEquals($parentEl->lastElementChild, null);
        });
    }
}
