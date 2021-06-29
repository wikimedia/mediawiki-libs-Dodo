<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-siblingElement-null.html.
class ElementSiblingElementNullTest extends WPTTestHarness
{
    public function testElementSiblingElementNull()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-siblingElement-null.html');
        $this->assertTest(function () {
            $fec = $this->doc->getElementById('first_element_child');
            $this->assertEqualsData($fec->previousElementSibling, null);
            $this->assertEqualsData($fec->nextElementSibling, null);
        });
    }
}
