<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-previousElementSibling.html.
class ElementPreviousElementSiblingTest extends WPTTestHarness
{
    public function testElementPreviousElementSibling()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-previousElementSibling.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $lec = $this->doc->getElementById('last_element_child');
            $pes = $lec->previousElementSibling;
            $this->wptAssertTrue(!!$pes);
            $this->wptAssertEquals($pes->nodeType, 1);
            $this->wptAssertEquals($pes->getAttribute('id'), 'middle_element_child');
        });
    }
}
