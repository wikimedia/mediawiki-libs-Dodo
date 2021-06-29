<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
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
            $this->assertEqualsData($clonedRange->startContainer, $range->startContainer, 'startContainers must be equal after cloneRange()');
            $this->assertEqualsData($clonedRange->startOffset, $range->startOffset, 'startOffsets must be equal after cloneRange()');
            $this->assertEqualsData($clonedRange->endContainer, $range->endContainer, 'endContainers must be equal after cloneRange()');
            $this->assertEqualsData($clonedRange->endOffset, $range->endOffset, 'endOffsets must be equal after cloneRange()');
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
        $this->assertEqualsData($range->startContainer, $ownerDoc, 'doc.createRange() must create Range whose startContainer is doc');
        $this->assertEqualsData($range->endContainer, $ownerDoc, 'doc.createRange() must create Range whose endContainer is doc');
        $this->assertEqualsData($range->startOffset, 0, 'doc.createRange() must create Range whose startOffset is 0');
        $this->assertEqualsData($range->endOffset, 0, 'doc.createRange() must create Range whose endOffset is 0');
        $range->setStart($rangeEndpoints[0], $rangeEndpoints[1]);
        $range->setEnd($rangeEndpoints[2], $rangeEndpoints[3]);
        // Make sure we bail out now if setStart or setEnd are buggy, so it doesn't
        // create misleading failures later.
        $this->assertEqualsData($range->startContainer, $rangeEndpoints[0], 'Sanity check on setStart()');
        $this->assertEqualsData($range->startOffset, $rangeEndpoints[1], 'Sanity check on setStart()');
        $this->assertEqualsData($range->endContainer, $rangeEndpoints[2], 'Sanity check on setEnd()');
        $this->assertEqualsData($range->endOffset, $rangeEndpoints[3], 'Sanity check on setEnd()');
        $clonedRange = $range->cloneRange();
        $this->assertEqualsData($clonedRange->startContainer, $range->startContainer, 'startContainers must be equal after cloneRange()');
        $this->assertEqualsData($clonedRange->startOffset, $range->startOffset, 'startOffsets must be equal after cloneRange()');
        $this->assertEqualsData($clonedRange->endContainer, $range->endContainer, 'endContainers must be equal after cloneRange()');
        $this->assertEqualsData($clonedRange->endOffset, $range->endOffset, 'endOffsets must be equal after cloneRange()');
        // Make sure that modifying one doesn't affect the other.
        $testNode1 = $ownerDoc->createTextNode('testing');
        $testNode2 = $ownerDoc->createTextNode('testing with different length');
        $range->setStart($testNode1, 1);
        $range->setEnd($testNode1, 2);
        $this->assertEqualsData($clonedRange->startContainer, $rangeEndpoints[0], "Modifying a Range must not modify its clone's startContainer");
        $this->assertEqualsData($clonedRange->startOffset, $rangeEndpoints[1], "Modifying a Range must not modify its clone's startOffset");
        $this->assertEqualsData($clonedRange->endContainer, $rangeEndpoints[2], "Modifying a Range must not modify its clone's endContainer");
        $this->assertEqualsData($clonedRange->endOffset, $rangeEndpoints[3], "Modifying a Range must not modify its clone's endOffset");
        $clonedRange->setStart($testNode2, 3);
        $clonedRange->setStart($testNode2, 4);
        $this->assertEqualsData($range->startContainer, $testNode1, "Modifying a clone must not modify the original Range's startContainer");
        $this->assertEqualsData($range->startOffset, 1, "Modifying a clone must not modify the original Range's startOffset");
        $this->assertEqualsData($range->endContainer, $testNode1, "Modifying a clone must not modify the original Range's endContainer");
        $this->assertEqualsData($range->endOffset, 2, "Modifying a clone must not modify the original Range's endOffset");
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
