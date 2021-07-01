<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html.
class RangeAttributesTest extends WPTTestHarness
{
    public function testRangeAttributes()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $this->wptAssertEquals($r->startContainer, $this->doc);
            $this->wptAssertEquals($r->endContainer, $this->doc);
            $this->wptAssertEquals($r->startOffset, 0);
            $this->wptAssertEquals($r->endOffset, 0);
            $this->wptAssertTrue($r->collapsed);
            $r->detach();
            $this->wptAssertEquals($r->startContainer, $this->doc);
            $this->wptAssertEquals($r->endContainer, $this->doc);
            $this->wptAssertEquals($r->startOffset, 0);
            $this->wptAssertEquals($r->endOffset, 0);
            $this->wptAssertTrue($r->collapsed);
        });
    }
}
