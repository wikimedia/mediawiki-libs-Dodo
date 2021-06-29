<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
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
            $this->assertTrueData(!!$nes);
            $this->assertEqualsData($nes->nodeType, 1);
            $this->assertEqualsData($nes->getAttribute('id'), 'last_element_child');
        });
    }
}
