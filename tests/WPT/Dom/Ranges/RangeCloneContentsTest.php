<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-cloneContents.html.
class RangeCloneContentsTest extends WPTTestHarness
{
    public function myCloneContents($range)
    {
        // "Let frag be a new DocumentFragment whose ownerDocument is the same as
        // the ownerDocument of the context object's start node."
        $ownerDoc = $range->startContainer->nodeType == Node::DOCUMENT_NODE ? $range->startContainer : $range->startContainer->ownerDocument;
        $frag = $ownerDoc->createDocumentFragment();
        // "If the context object's start and end are the same, abort this method,
        // returning frag."
        if ($range->startContainer == $range->endContainer && $range->startOffset == $range->endOffset) {
            return $frag;
        }
        // "Let original start node, original start offset, original end node, and
        // original end offset be the context object's start and end nodes and
        // offsets, respectively."
        $originalStartNode = $range->startContainer;
        $originalStartOffset = $range->startOffset;
        $originalEndNode = $range->endContainer;
        $originalEndOffset = $range->endOffset;
        // "If original start node and original end node are the same, and they are
        // a Text, ProcessingInstruction, or Comment node:"
        if ($range->startContainer == $range->endContainer && ($range->startContainer->nodeType == Node::TEXT_NODE || $range->startContainer->nodeType == Node::COMMENT_NODE || $range->startContainer->nodeType == Node::PROCESSING_INSTRUCTION_NODE)) {
            // "Let clone be the result of calling cloneNode(false) on original
            // start node."
            $clone = $originalStartNode->cloneNode(false);
            // "Set the data of clone to the result of calling
            // substringData(original start offset, original end offset − original
            // start offset) on original start node."
            $clone->data = $originalStartNode->substringData($originalStartOffset, $originalEndOffset - $originalStartOffset);
            // "Append clone as the last child of frag."
            $frag->appendChild($clone);
            // "Abort this method, returning frag."
            return $frag;
        }
        // "Let common ancestor equal original start node."
        $commonAncestor = $originalStartNode;
        // "While common ancestor is not an ancestor container of original end
        // node, set common ancestor to its own parent."
        while (!Common::isAncestorContainer($commonAncestor, $originalEndNode)) {
            $commonAncestor = $commonAncestor->parentNode;
        }
        // "If original start node is an ancestor container of original end node,
        // let first partially contained child be null."
        $firstPartiallyContainedChild = null;
        if (Common::isAncestorContainer($originalStartNode, $originalEndNode)) {
            $firstPartiallyContainedChild = null;
            // "Otherwise, let first partially contained child be the first child of
            // common ancestor that is partially contained in the context object."
        } else {
            for ($i = 0; $i < count($commonAncestor->childNodes); $i++) {
                if (Common::isPartiallyContained($commonAncestor->childNodes[$i], $range)) {
                    $firstPartiallyContainedChild = $commonAncestor->childNodes[$i];
                    break;
                }
            }
            if (!$firstPartiallyContainedChild) {
                throw 'Spec bug: no first partially contained child!';
            }
        }
        // "If original end node is an ancestor container of original start node,
        // let last partially contained child be null."
        $lastPartiallyContainedChild = null;
        if (Common::isAncestorContainer($originalEndNode, $originalStartNode)) {
            $lastPartiallyContainedChild = null;
            // "Otherwise, let last partially contained child be the last child of
            // common ancestor that is partially contained in the context object."
        } else {
            for ($i = count($commonAncestor->childNodes) - 1; $i >= 0; $i--) {
                if (Common::isPartiallyContained($commonAncestor->childNodes[$i], $range)) {
                    $lastPartiallyContainedChild = $commonAncestor->childNodes[$i];
                    break;
                }
            }
            if (!$lastPartiallyContainedChild) {
                throw 'Spec bug: no last partially contained child!';
            }
        }
        // "Let contained children be a list of all children of common ancestor
        // that are contained in the context object, in tree order."
        //
        // "If any member of contained children is a DocumentType, raise a
        // HIERARCHY_REQUEST_ERR exception and abort these steps."
        $containedChildren = [];
        for ($i = 0; $i < count($commonAncestor->childNodes); $i++) {
            if (Common::isContained($commonAncestor->childNodes[$i], $range)) {
                if ($commonAncestor->childNodes[$i]->nodeType == Node::DOCUMENT_TYPE_NODE) {
                    return 'HIERARCHY_REQUEST_ERR';
                }
                $containedChildren[] = $commonAncestor->childNodes[$i];
            }
        }
        // "If first partially contained child is a Text, ProcessingInstruction, or Comment node:"
        if ($firstPartiallyContainedChild && ($firstPartiallyContainedChild->nodeType == Node::TEXT_NODE || $firstPartiallyContainedChild->nodeType == Node::COMMENT_NODE || $firstPartiallyContainedChild->nodeType == Node::PROCESSING_INSTRUCTION_NODE)) {
            // "Let clone be the result of calling cloneNode(false) on original
            // start node."
            $clone = $originalStartNode->cloneNode(false);
            // "Set the data of clone to the result of calling substringData() on
            // original start node, with original start offset as the first
            // argument and (length of original start node − original start offset)
            // as the second."
            $clone->data = $originalStartNode->substringData($originalStartOffset, Common::nodeLength($originalStartNode) - $originalStartOffset);
            // "Append clone as the last child of frag."
            $frag->appendChild($clone);
            // "Otherwise, if first partially contained child is not null:"
        } else {
            if ($firstPartiallyContainedChild) {
                // "Let clone be the result of calling cloneNode(false) on first
                // partially contained child."
                $clone = $firstPartiallyContainedChild->cloneNode(false);
                // "Append clone as the last child of frag."
                $frag->appendChild($clone);
                // "Let subrange be a new Range whose start is (original start node,
                // original start offset) and whose end is (first partially contained
                // child, length of first partially contained child)."
                $subrange = $ownerDoc->createRange();
                $subrange->setStart($originalStartNode, $originalStartOffset);
                $subrange->setEnd($firstPartiallyContainedChild, Common::nodeLength($firstPartiallyContainedChild));
                // "Let subfrag be the result of calling cloneContents() on
                // subrange."
                $subfrag = $this->myCloneContents($subrange);
                // "For each child of subfrag, in order, append that child to clone as
                // its last child."
                for ($i = 0; $i < count($subfrag->childNodes); $i++) {
                    $clone->appendChild($subfrag->childNodes[$i]);
                }
            }
        }
        // "For each contained child in contained children:"
        for ($i = 0; $i < count($containedChildren); $i++) {
            // "Let clone be the result of calling cloneNode(true) of contained
            // child."
            $clone = $containedChildren[$i]->cloneNode(true);
            // "Append clone as the last child of frag."
            $frag->appendChild($clone);
        }
        // "If last partially contained child is a Text, ProcessingInstruction, or Comment node:"
        if ($lastPartiallyContainedChild && ($lastPartiallyContainedChild->nodeType == Node::TEXT_NODE || $lastPartiallyContainedChild->nodeType == Node::COMMENT_NODE || $lastPartiallyContainedChild->nodeType == Node::PROCESSING_INSTRUCTION_NODE)) {
            // "Let clone be the result of calling cloneNode(false) on original
            // end node."
            $clone = $originalEndNode->cloneNode(false);
            // "Set the data of clone to the result of calling substringData(0,
            // original end offset) on original end node."
            $clone->data = $originalEndNode->substringData(0, $originalEndOffset);
            // "Append clone as the last child of frag."
            $frag->appendChild($clone);
            // "Otherwise, if last partially contained child is not null:"
        } else {
            if ($lastPartiallyContainedChild) {
                // "Let clone be the result of calling cloneNode(false) on last
                // partially contained child."
                $clone = $lastPartiallyContainedChild->cloneNode(false);
                // "Append clone as the last child of frag."
                $frag->appendChild($clone);
                // "Let subrange be a new Range whose start is (last partially
                // contained child, 0) and whose end is (original end node, original
                // end offset)."
                $subrange = $ownerDoc->createRange();
                $subrange->setStart($lastPartiallyContainedChild, 0);
                $subrange->setEnd($originalEndNode, $originalEndOffset);
                // "Let subfrag be the result of calling cloneContents() on
                // subrange."
                $subfrag = $this->myCloneContents($subrange);
                // "For each child of subfrag, in order, append that child to clone as
                // its last child."
                for ($i = 0; $i < count($subfrag->childNodes); $i++) {
                    $clone->appendChild($subfrag->childNodes[$i]);
                }
            }
        }
        // "Return frag."
        return $frag;
    }
    public function restoreIframe($iframe, $i)
    {
        // Most of this function is designed to work around the fact that Opera
        // doesn't let you add a doctype to a document that no longer has one, in
        // any way I can figure out.  I eventually compromised on something that
        // will still let Opera pass most tests that don't actually involve
        // doctypes.
        while ($iframe->getOwnerDocument()->firstChild && $iframe->getOwnerDocument()->firstChild->nodeType != Node::DOCUMENT_TYPE_NODE) {
            $iframe->getOwnerDocument()->removeChild($iframe->getOwnerDocument()->firstChild);
        }
        while ($iframe->getOwnerDocument()->lastChild && $iframe->getOwnerDocument()->lastChild->nodeType != Node::DOCUMENT_TYPE_NODE) {
            $iframe->getOwnerDocument()->removeChild($iframe->getOwnerDocument()->lastChild);
        }
        if (!$iframe->getOwnerDocument()->firstChild) {
            // This will throw an exception in Opera if we reach here, which is why
            // I try to avoid it.  It will never happen in a browser that obeys the
            // spec, so it's really just insurance.  I don't think it actually gets
            // hit by anything.
            $iframe->getOwnerDocument()->appendChild($iframe->getOwnerDocument()->implementation->createDocumentType('html', '', ''));
        }
        $iframe->getOwnerDocument()->appendChild($referenceDoc->documentElement->cloneNode(true));
        $iframe->contentWindow->setupRangeTests();
        $iframe->contentWindow->testRangeInput = $this->getCommon()->testRanges[$i];
        $iframe->contentWindow->run();
    }
    public function helperTestCloneContents($i)
    {
        global $actualIframe;
        global $expectedIframe;
        $this->restoreIframe($actualIframe, $i);
        $this->restoreIframe($expectedIframe, $i);
        $actualRange = $actualIframe->contentWindow->testRange;
        $expectedRange = $expectedIframe->contentWindow->testRange;
        $actualFrag = null;
        $expectedFrag = null;
        $actualRoots = null;
        $expectedRoots = null;
        $domTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual cloneContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated cloneContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            // NOTE: We could just assume that cloneContents() doesn't change
            // anything.  That would simplify these tests, taken in isolation.  But
            // once we've already set up the whole apparatus for extractContents()
            // and deleteContents(), we just reuse it here, on the theory of "why
            // not test some more stuff if it's easy to do".
            //
            // Just to be pedantic, we'll test not only that the tree we're
            // modifying is the same in expected vs. actual, but also that all the
            // nodes originally in it were the same.  Typically some nodes will
            // become detached when the algorithm is run, but they still exist and
            // references can still be kept to them, so they should also remain the
            // same.
            //
            // We initialize the list to all nodes, and later on remove all the
            // ones which still have parents, since the parents will presumably be
            // tested for isEqualNode() and checking the children would be
            // redundant.
            $actualAllNodes = [];
            $node = Common::furthestAncestor($actualRange->startContainer);
            do {
                $actualAllNodes[] = $node;
            } while ($node = Common::nextNode($node));
            $expectedAllNodes = [];
            $node = Common::furthestAncestor($expectedRange->startContainer);
            do {
                $expectedAllNodes[] = $node;
            } while ($node = Common::nextNode($node));
            $expectedFrag = $this->myCloneContents($expectedRange);
            if (gettype($expectedFrag) == 'string') {
                $this->wptAssertThrowsDom($expectedFrag, $actualIframe->contentWindow->DOMException, function () use(&$actualRange) {
                    $actualRange->cloneContents();
                });
            } else {
                $actualFrag = $actualRange->cloneContents();
            }
            $actualRoots = [];
            for ($j = 0; $j < count($actualAllNodes); $j++) {
                if (!$actualAllNodes[$j]->parentNode) {
                    $actualRoots[] = $actualAllNodes[$j];
                }
            }
            $expectedRoots = [];
            for ($j = 0; $j < count($expectedAllNodes); $j++) {
                if (!$expectedAllNodes[$j]->parentNode) {
                    $expectedRoots[] = $expectedAllNodes[$j];
                }
            }
            for ($j = 0; $j < count($actualRoots); $j++) {
                Common::assertNodesEqual($actualRoots[$j], $expectedRoots[$j], $j ? 'detached node #' . $j : 'tree root');
                if ($j == 0) {
                    // Clearly something is wrong if the node lists are different
                    // lengths.  We want to report this only after we've already
                    // checked the main tree for equality, though, so it doesn't
                    // mask more interesting errors.
                    $this->wptAssertEquals(count($actualRoots), count($expectedRoots), "Actual and expected DOMs were broken up into a different number of pieces by cloneContents() (this probably means you created or detached nodes when you weren't supposed to)");
                }
            }
        });
        $domTests[$i]->done();
        $positionTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange, &$actualRoots, &$expectedRoots, &$expectedFrag) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual cloneContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated cloneContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            $this->wptAssertTrue($actualRoots[0]->isEqualNode($expectedRoots[0]), 'The resulting DOMs were not equal, so comparing positions makes no sense');
            if (gettype($expectedFrag) == 'string') {
                // It's no longer true that, e.g., startContainer and endContainer
                // must always be the same
                return;
            }
            $this->wptAssertEquals($actualRange->startOffset, $expectedRange->startOffset, 'Unexpected startOffset after cloneContents()');
            // How do we decide that the two nodes are equal, since they're in
            // different trees?  Since the DOMs are the same, it's enough to check
            // that the index in the parent is the same all the way up the tree.
            // But we can first cheat by just checking they're actually equal.
            $this->wptAssertTrue($actualRange->startContainer->isEqualNode($expectedRange->startContainer), 'Unexpected startContainer after cloneContents(), expected ' . strtolower($expectedRange->startContainer->nodeName) . ' but got ' . strtolower($actualRange->startContainer->nodeName));
            $currentActual = $actualRange->startContainer;
            $currentExpected = $expectedRange->startContainer;
            $actual = '';
            $expected = '';
            while ($currentActual && $currentExpected) {
                $actual = Common::indexOf($currentActual) . '-' . $actual;
                $expected = Common::indexOf($currentExpected) . '-' . $expected;
                $currentActual = $currentActual->parentNode;
                $currentExpected = $currentExpected->parentNode;
            }
            $actual = substr($actual, 0, count($actual) - 1);
            $expected = substr($expected, 0, count($expected) - 1);
            $this->wptAssertEquals($actual, $expected, "startContainer superficially looks right but is actually the wrong node if you trace back its index in all its ancestors (I'm surprised this actually happened");
        });
        $positionTests[$i]->done();
        $fragTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange, &$expectedFrag, &$actualFrag) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual cloneContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated cloneContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            if (gettype($expectedFrag) == 'string') {
                // Comparing makes no sense
                return;
            }
            Common::assertNodesEqual($actualFrag, $expectedFrag, 'returned fragment');
        });
        $fragTests[$i]->done();
    }
    public function testRangeCloneContents()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-cloneContents.html');
        $this->getCommon()->testDiv->parentNode->removeChild($this->getCommon()->testDiv);
        $actualIframe = $this->doc->createElement('iframe');
        $actualIframe->style->display = 'none';
        $this->doc->body->appendChild($actualIframe);
        $expectedIframe = $this->doc->createElement('iframe');
        $expectedIframe->style->display = 'none';
        $this->doc->body->appendChild($expectedIframe);
        // First test a Range that has the no-op detach() called on it, synchronously
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $range->detach();
            $this->wptAssertArrayEquals($range->cloneContents()->childNodes, []);
        }, 'Range.detach()');
        $iStart = 0;
        $iStop = count($this->getCommon()->testRanges);
        if (preg_match('/subtest=[0-9]+/', $this->getLocation()->search)) {
            $matches = preg_match('/subtest=([0-9]+)/', $this->getLocation()->search, $FIXME);
            $iStart = intval($matches[1]);
            $iStop = intval($matches[1]) + 1;
        }
        $domTests = [];
        $positionTests = [];
        $fragTests = [];
        for ($i = $iStart; $i < $iStop; $i++) {
            $domTests[$i] = $this->asyncTest('Resulting DOM for range ' . $i . ' ' . $this->getCommon()->testRanges[$i]);
            $positionTests[$i] = $this->asyncTest('Resulting cursor position for range ' . $i . ' ' . $this->getCommon()->testRanges[$i]);
            $fragTests[$i] = $this->asyncTest('Returned fragment for range ' . $i . ' ' . $this->getCommon()->testRanges[$i]);
        }
        $referenceDoc = $this->doc->implementation->createHTMLDocument('');
        $referenceDoc->removeChild($referenceDoc->documentElement);
        $actualIframe->onload = function () use(&$expectedIframe, &$iStart, &$iStop, &$referenceDoc, &$actualIframe) {
            $expectedIframe->onload = function () use(&$iStart, &$iStop) {
                for ($i = $iStart; $i < $iStop; $i++) {
                    $this->helperTestCloneContents($i);
                }
            };
            $expectedIframe->src = 'Range-test-iframe.html';
            $referenceDoc->appendChild($actualIframe->getOwnerDocument()->documentElement->cloneNode(true));
        };
        $actualIframe->src = 'Range-test-iframe.html';
    }
}
