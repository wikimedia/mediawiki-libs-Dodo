<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-comparePoint-2.html.
class RangeComparePoint2Test extends WPTTestHarness
{
    public function testRangeComparePoint2()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-comparePoint-2.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $r->detach();
            $this->wptAssertEquals($r->comparePoint($this->doc->body, 0), 1);
        });
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $this->wptAssertThrowsJs($this->type_error, function () use(&$r) {
                $r->comparePoint(null, 0);
            });
        });
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('tralala');
            $r = $this->doc->createRange();
            $this->wptAssertThrowsDom('WRONG_DOCUMENT_ERR', function () use(&$r, &$doc) {
                $r->comparePoint($doc->body, 0);
            });
        });
    }
}
