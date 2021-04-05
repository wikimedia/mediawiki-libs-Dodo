<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount.html.
class ElementChildElementCountTest extends WptTestHarness
{
    public function testElementChildElementCount()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount.html';
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->assertTrueData(isset($parentEl['childElementCount']));
            $this->assertEqualsData($parentEl->childElementCount, 3);
        });
    }
}
