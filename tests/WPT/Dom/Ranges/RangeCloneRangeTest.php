<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-cloneRange.html.
class RangeCloneRangeTest extends WPTTestHarness
{
    public function testCloneRange($rangeEndpoints)
    {
        $range = null;
        if ($rangeEndpoints == 'detached') {
            $range = $this->doc->createRange();
            $range->detach();
            $clonedRange = $range->cloneRange();
            $this->wptAssertEquals($clonedRange->startContainer, $range->startContainer, 'startContainers must be equal after cloneRange()');
            $this->wptAssertEquals($clonedRange->startOffset, $range->startOffset, 'startOffsets must be equal after cloneRange()');
            $this->wptAssertEquals($clonedRange->endContainer, $range->endContainer, 'endContainers must be equal after cloneRange()');
            $this->wptAssertEquals($clonedRange->endOffset, $range->endOffset, 'endOffsets must be equal after cloneRange()');
            return;
        }
        // Have to account for Ranges involving Documents!  We could just create
        // the Range from the current document unconditionally, but some browsers
        // (WebKit) don't implement setStart() and setEnd() per spec and will throw
        // spurious exceptions at the time of this writing.  No need to mask other
        // bugs.
        $ownerDoc = $rangeEndpoints[0]->nodeType == Node::DOCUMENT_NODE ? $rangeEndpoints[0] : $rangeEndpoints[0]->ownerDocument;
        $range = $ownerDoc->createRange();
        // Here we throw in some createRange() tests, because why not.  Have to
        // test it someplace.
        $this->wptAssertEquals($range->startContainer, $ownerDoc, 'doc.createRange() must create Range whose startContainer is doc');
        $this->wptAssertEquals($range->endContainer, $ownerDoc, 'doc.createRange() must create Range whose endContainer is doc');
        $this->wptAssertEquals($range->startOffset, 0, 'doc.createRange() must create Range whose startOffset is 0');
        $this->wptAssertEquals($range->endOffset, 0, 'doc.createRange() must create Range whose endOffset is 0');
        $range->setStart($rangeEndpoints[0], $rangeEndpoints[1]);
        $range->setEnd($rangeEndpoints[2], $rangeEndpoints[3]);
        // Make sure we bail out now if setStart or setEnd are buggy, so it doesn't
        // create misleading failures later.
        $this->wptAssertEquals($range->startContainer, $rangeEndpoints[0], 'Sanity check on setStart()');
        $this->wptAssertEquals($range->startOffset, $rangeEndpoints[1], 'Sanity check on setStart()');
        $this->wptAssertEquals($range->endContainer, $rangeEndpoints[2], 'Sanity check on setEnd()');
        $this->wptAssertEquals($range->endOffset, $rangeEndpoints[3], 'Sanity check on setEnd()');
        $clonedRange = $range->cloneRange();
        $this->wptAssertEquals($clonedRange->startContainer, $range->startContainer, 'startContainers must be equal after cloneRange()');
        $this->wptAssertEquals($clonedRange->startOffset, $range->startOffset, 'startOffsets must be equal after cloneRange()');
        $this->wptAssertEquals($clonedRange->endContainer, $range->endContainer, 'endContainers must be equal after cloneRange()');
        $this->wptAssertEquals($clonedRange->endOffset, $range->endOffset, 'endOffsets must be equal after cloneRange()');
        // Make sure that modifying one doesn't affect the other.
        $testNode1 = $ownerDoc->createTextNode('testing');
        $testNode2 = $ownerDoc->createTextNode('testing with different length');
        $range->setStart($testNode1, 1);
        $range->setEnd($testNode1, 2);
        $this->wptAssertEquals($clonedRange->startContainer, $rangeEndpoints[0], "Modifying a Range must not modify its clone's startContainer");
        $this->wptAssertEquals($clonedRange->startOffset, $rangeEndpoints[1], "Modifying a Range must not modify its clone's startOffset");
        $this->wptAssertEquals($clonedRange->endContainer, $rangeEndpoints[2], "Modifying a Range must not modify its clone's endContainer");
        $this->wptAssertEquals($clonedRange->endOffset, $rangeEndpoints[3], "Modifying a Range must not modify its clone's endOffset");
        $clonedRange->setStart($testNode2, 3);
        $clonedRange->setStart($testNode2, 4);
        $this->wptAssertEquals($range->startContainer, $testNode1, "Modifying a clone must not modify the original Range's startContainer");
        $this->wptAssertEquals($range->startOffset, 1, "Modifying a clone must not modify the original Range's startOffset");
        $this->wptAssertEquals($range->endContainer, $testNode1, "Modifying a clone must not modify the original Range's endContainer");
        $this->wptAssertEquals($range->endOffset, 2, "Modifying a clone must not modify the original Range's endOffset");
    }
    public function testRangeCloneRange()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-cloneRange.html');
        $tests = [];
        for ($i = 0; $i < count($testRanges); $i++) {
            $tests[] = ['Range ' . $i . ' ' . $testRanges[$i], eval($testRanges[$i])];
        }
        $this->generateTests($testCloneRange, $tests);
        $testDiv->style->display = 'none';
    }
}
