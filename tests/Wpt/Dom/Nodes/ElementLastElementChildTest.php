<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-lastElementChild.html.
class ElementLastElementChildTest extends WptTestHarness
{
    public function testElementLastElementChild()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-lastElementChild.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $lec = $parentEl->lastElementChild;
            $this->assertTrueData(!!$lec);
            $this->assertEqualsData($lec->nodeType, 1);
            $this->assertEqualsData($lec->getAttribute('id'), 'last_element_child');
        });
    }
}
