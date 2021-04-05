<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElement-null.html.
class ElementChildElementNullTest extends WptTestHarness
{
    public function testElementChildElementNull()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-childElement-null.html';
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->assertEqualsData($parentEl->firstElementChild, null);
            $this->assertEqualsData($parentEl->lastElementChild, null);
        });
    }
}
