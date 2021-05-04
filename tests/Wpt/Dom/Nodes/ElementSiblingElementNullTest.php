<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-siblingElement-null.html.
class ElementSiblingElementNullTest extends WptTestHarness
{
    public function testElementSiblingElementNull()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-siblingElement-null.html');
        $this->assertTest(function () {
            $fec = $this->doc->getElementById('first_element_child');
            $this->assertEqualsData($fec->previousElementSibling, null);
            $this->assertEqualsData($fec->nextElementSibling, null);
        });
    }
}
