<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-comparePoint-2.html.
class RangeComparePoint2Test extends WptTestHarness
{
    public function testRangeComparePoint2()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-comparePoint-2.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $r->detach();
            $this->assertEqualsData($r->comparePoint($this->getDocBody( $this->doc ), 0), 1);
        });
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->comparePoint(null, 0);
            });
        });
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('tralala');
            $r = $this->doc->createRange();
            $this->assertThrowsDomData('WRONG_DOCUMENT_ERR', function () use(&$r, &$doc) {
                $r->comparePoint($doc->body, 0);
            });
        });
    }
}
