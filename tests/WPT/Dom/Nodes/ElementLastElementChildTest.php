<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-lastElementChild.html.
class ElementLastElementChildTest extends WPTTestHarness
{
    public function testElementLastElementChild()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-lastElementChild.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $lec = $parentEl->lastElementChild;
            $this->assertTrueData(!!$lec);
            $this->assertEqualsData($lec->nodeType, 1);
            $this->assertEqualsData($lec->getAttribute('id'), 'last_element_child');
        });
    }
}
