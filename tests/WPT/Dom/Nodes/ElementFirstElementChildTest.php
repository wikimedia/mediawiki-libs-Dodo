<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild.html.
class ElementFirstElementChildTest extends WPTTestHarness
{
    public function testElementFirstElementChild()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $fec = $parentEl->firstElementChild;
            $this->assertTrueData(!!$fec);
            $this->assertEqualsData($fec->nodeType, 1);
            $this->assertEqualsData($fec->getAttribute('id'), 'first_element_child');
        });
    }
}
