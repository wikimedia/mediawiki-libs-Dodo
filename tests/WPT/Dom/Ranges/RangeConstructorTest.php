<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-constructor.html.
class RangeConstructorTest extends WPTTestHarness
{
    public function testRangeConstructor()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-constructor.html');
        $this->assertTest(function () {
            $range = new Range();
            $this->wptAssertEquals($range->startContainer, $this->doc, 'startContainer');
            $this->wptAssertEquals($range->endContainer, $this->doc, 'endContainer');
            $this->wptAssertEquals($range->startOffset, 0, 'startOffset');
            $this->wptAssertEquals($range->endOffset, 0, 'endOffset');
            $this->wptAssertTrue($range->collapsed, 'collapsed');
            $this->wptAssertEquals($range->commonAncestorContainer, $this->doc, 'commonAncestorContainer');
        });
    }
}
