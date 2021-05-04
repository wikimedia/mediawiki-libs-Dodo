<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-nochild.html.
class ElementChildElementCountNochildTest extends WptTestHarness
{
    public function testElementChildElementCountNochild()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-childElementCount-nochild.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $this->assertEqualsData($parentEl->childElementCount, 0);
        });
    }
}
