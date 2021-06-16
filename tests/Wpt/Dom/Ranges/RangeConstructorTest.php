<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-constructor.html.
class RangeConstructorTest extends WptTestHarness
{
    public function testRangeConstructor()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-constructor.html');
        $this->assertTest(function () {
            $range = new Range();
            $this->assertEqualsData($range->startContainer, $this->doc, 'startContainer');
            $this->assertEqualsData($range->endContainer, $this->doc, 'endContainer');
            $this->assertEqualsData($range->startOffset, 0, 'startOffset');
            $this->assertEqualsData($range->endOffset, 0, 'endOffset');
            $this->assertTrueData($range->collapsed, 'collapsed');
            $this->assertEqualsData($range->commonAncestorContainer, $this->doc, 'commonAncestorContainer');
        });
    }
}
