<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-detach.html.
class RangeDetachTest extends WptTestHarness
{
    public function testRangeDetach()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/ranges/Range-detach.html';
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $r->detach();
            $r->detach();
        });
    }
}
