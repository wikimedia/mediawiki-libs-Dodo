<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-nextElementSibling.html.
class ElementNextElementSiblingTest extends WptTestHarness
{
    public function testElementNextElementSibling()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-nextElementSibling.html');
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
