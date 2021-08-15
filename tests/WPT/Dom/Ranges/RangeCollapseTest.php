<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-collapse.html.
class RangeCollapseTest extends WPTTestHarness
{
    public function helperTestCollapse($rangeEndpoints, $toStart)
    {
        $range = null;
        if ($rangeEndpoints == 'detached') {
            $range = $this->doc->createRange();
            $range->detach();
            // should be a no-op and therefore the following should not throw
            $range->collapse($toStart);
            $this->wptAssertEquals(true, $range->collapsed);
        }
        // Have to account for Ranges involving Documents!
        $ownerDoc = $rangeEndpoints[0]->nodeType == Node::DOCUMENT_NODE ? $rangeEndpoints[0] : $rangeEndpoints[0]->ownerDocument;
        $range = $ownerDoc->createRange();
        $range->setStart($rangeEndpoints[0], $rangeEndpoints[1]);
        $range->setEnd($rangeEndpoints[2], $rangeEndpoints[3]);
        $expectedContainer = $toStart ? $range->startContainer : $range->endContainer;
        $expectedOffset = $toStart ? $range->startOffset : $range->endOffset;
        $this->wptAssertEquals($range->collapsed, $range->startContainer == $range->endContainer && $range->startOffset == $range->endOffset, 'collapsed must be true if and only if the start and end are equal');
        if ($toStart === null) {
            $range->collapse();
        } else {
            $range->collapse($toStart);
        }
        $this->wptAssertEquals($range->startContainer, $expectedContainer, 'Wrong startContainer');
        $this->wptAssertEquals($range->endContainer, $expectedContainer, 'Wrong endContainer');
        $this->wptAssertEquals($range->startOffset, $expectedOffset, 'Wrong startOffset');
        $this->wptAssertEquals($range->endOffset, $expectedOffset, 'Wrong endOffset');
        $this->wptAssertTrue($range->collapsed, '.collapsed must be set after .collapsed()');
    }
    public function testRangeCollapse()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-collapse.html');
        $tests = [];
        for ($i = 0; $i < count($this->getCommon()->testRanges); $i++) {
            $tests[] = ['Range ' . $i . ' ' . $this->getCommon()->testRanges[$i] . ', toStart true', eval($this->getCommon()->testRanges[$i]), true];
            $tests[] = ['Range ' . $i . ' ' . $this->getCommon()->testRanges[$i] . ', toStart false', eval($this->getCommon()->testRanges[$i]), false];
            $tests[] = ['Range ' . $i . ' ' . $this->getCommon()->testRanges[$i] . ', toStart omitted', eval($this->getCommon()->testRanges[$i]), null];
        }
        $this->generateTests([$this, 'helperTestCollapse'], $tests);
        $this->getCommon()->testDiv->style->display = 'none';
    }
}
