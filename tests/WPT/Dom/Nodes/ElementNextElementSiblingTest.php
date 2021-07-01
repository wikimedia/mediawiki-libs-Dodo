<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-nextElementSibling.html.
class ElementNextElementSiblingTest extends WPTTestHarness
{
    public function testElementNextElementSibling()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-nextElementSibling.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $fec = $this->doc->getElementById('first_element_child');
            $nes = $fec->nextElementSibling;
            $this->wptAssertTrue(!!$nes);
            $this->wptAssertEquals($nes->nodeType, 1);
            $this->wptAssertEquals($nes->getAttribute('id'), 'last_element_child');
        });
    }
}
