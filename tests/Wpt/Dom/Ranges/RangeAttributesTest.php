<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html.
class RangeAttributesTest extends WptTestHarness
{
    public function testRangeAttributes()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-attributes.html');
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
