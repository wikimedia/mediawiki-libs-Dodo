<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-add.html.
class ElementChildElementCountDynamicAddTest extends WptTestHarness
{
    public function testElementChildElementCountDynamicAdd()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-dynamic-add.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $newChild = $this->doc->createElement('span');
            $parentEl->appendChild($newChild);
            $this->assertEqualsData($parentEl->childElementCount, 2);
        });
    }
}
