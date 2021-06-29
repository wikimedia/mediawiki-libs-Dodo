<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html.
class RangeAttributesTest extends WPTTestHarness
{
    public function testRangeAttributes()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $this->assertEqualsData($r->startContainer, $this->doc);
            $this->assertEqualsData($r->endContainer, $this->doc);
            $this->assertEqualsData($r->startOffset, 0);
            $this->assertEqualsData($r->endOffset, 0);
            $this->assertTrueData($r->collapsed);
            $r->detach();
            $this->assertEqualsData($r->startContainer, $this->doc);
            $this->assertEqualsData($r->endContainer, $this->doc);
            $this->assertEqualsData($r->startOffset, 0);
            $this->assertEqualsData($r->endOffset, 0);
            $this->assertTrueData($r->collapsed);
        });
    }
}
