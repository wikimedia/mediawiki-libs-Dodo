<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-detach.html.
class RangeDetachTest extends WPTTestHarness
{
    public function testRangeDetach()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-detach.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $r->detach();
            $r->detach();
        });
    }
}
