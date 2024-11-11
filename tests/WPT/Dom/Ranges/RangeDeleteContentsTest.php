<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-deleteContents.html.
class RangeDeleteContentsTest extends WPTTestHarness
{
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
    public function myDeleteContents($range)
    {
        // "If the context object's start and end are the same, abort this method."
        if ($range->startContainer == $range->endContainer && $range->startOffset == $range->endOffset) {
            return;
        }
        // "Let original start node, original start offset, original end node, and
        // original end offset be the context object's start and end nodes and
        // offsets, respectively."
        $originalStartNode = $range->startContainer;
        $originalStartOffset = $range->startOffset;
        $originalEndNode = $range->endContainer;
        $originalEndOffset = $range->endOffset;
        // "If original start node and original end node are the same, and they are
        // a Text, ProcessingInstruction, or Comment node, replace data with node
        // original start node, offset original start offset, count original end
        // offset minus original start offset, and data the empty string, and then
        // terminate these steps"
        if ($originalStartNode == $originalEndNode && ($range->startContainer->nodeType == Node::TEXT_NODE || $range->startContainer->nodeType == Node::PROCESSING_INSTRUCTION_NODE || $range->startContainer->nodeType == Node::COMMENT_NODE)) {
            $originalStartNode->deleteData($originalStartOffset, $originalEndOffset - $originalStartOffset);
            return;
        }
        // "Let nodes to remove be a list of all the Nodes that are contained in
        // the context object, in tree order, omitting any Node whose parent is
        // also contained in the context object."
        //
        // We rely on the fact that the contained nodes must lie in tree order
        // between the start node, and the end node's last descendant (inclusive).
        $nodesToRemove = [];
        $stop = Common::nextNodeDescendants($range->endContainer);
        for ($node = $range->startContainer; $node != $stop; $node = Common::nextNode($node)) {
            if (Common::isContained($node, $range) && !($node->parentNode && Common::isContained($node->parentNode, $range))) {
                $nodesToRemove[] = $node;
            }
        }
        // "If original start node is an ancestor container of original end node,
        // set new node to original start node and new offset to original start
        // offset."
        $newNode = null;
        $newOffset = null;
        if ($originalStartNode == $originalEndNode || $originalEndNode->compareDocumentPosition($originalStartNode) & Node::DOCUMENT_POSITION_CONTAINS) {
            $newNode = $originalStartNode;
            $newOffset = $originalStartOffset;
            // "Otherwise:"
        } else {
            // "Let reference node equal original start node."
            $referenceNode = $originalStartNode;
            // "While reference node's parent is not null and is not an ancestor
            // container of original end node, set reference node to its parent."
            while ($referenceNode->parentNode && $referenceNode->parentNode != $originalEndNode && !($originalEndNode->compareDocumentPosition($referenceNode->parentNode) & Node::DOCUMENT_POSITION_CONTAINS)) {
                $referenceNode = $referenceNode->parentNode;
            }
            // "Set new node to the parent of reference node, and new offset to one
            // plus the index of reference node."
            $newNode = $referenceNode->parentNode;
            $newOffset = 1 + Common::indexOf($referenceNode);
        }
        // "If original start node is a Text, ProcessingInstruction, or Comment node,
        // replace data with node original start node, offset original start offset,
        // count original start node's length minus original start offset, data the
        // empty start"
        if ($originalStartNode->nodeType == Node::TEXT_NODE || $originalStartNode->nodeType == Node::PROCESSING_INSTRUCTION_NODE || $originalStartNode->nodeType == Node::COMMENT_NODE) {
            $originalStartNode->deleteData($originalStartOffset, Common::nodeLength($originalStartNode) - $originalStartOffset);
        }
        // "For each node in nodes to remove, in order, remove node from its
        // parent."
        for ($i = 0; $i < count($nodesToRemove); $i++) {
            $nodesToRemove[$i]->parentNode->removeChild($nodesToRemove[$i]);
        }
        // "If original end node is a Text, ProcessingInstruction, or Comment node,
        // replace data with node original end node, offset 0, count original end
        // offset, and data the empty string."
        if ($originalEndNode->nodeType == Node::TEXT_NODE || $originalEndNode->nodeType == Node::PROCESSING_INSTRUCTION_NODE || $originalEndNode->nodeType == Node::COMMENT_NODE) {
            $originalEndNode->deleteData(0, $originalEndOffset);
        }
        // "Set the context object's start and end to (new node, new offset)."
        $range->setStart($newNode, $newOffset);
        $range->setEnd($newNode, $newOffset);
    }
    public function helperTestDeleteContents($i)
    {
        global $actualIframe;
        global $expectedIframe;
        $this->restoreIframe($actualIframe, $i);
        $this->restoreIframe($expectedIframe, $i);
        $actualRange = $actualIframe->contentWindow->testRange;
        $expectedRange = $expectedIframe->contentWindow->testRange;
        $actualRoots = null;
        $expectedRoots = null;
        $domTests[$i]->step(function () use (&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual deleteContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated deleteContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
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
            $actualRange->deleteContents();
            myDeleteContents($expectedRange);
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
                if (!$actualRoots[$j]->isEqualNode($expectedRoots[$j])) {
                    $msg = $j ? 'detached node #' . $j : 'tree root';
                    $msg = 'Actual and expected mismatch for ' . $msg . '.  ';
                    // Find the specific error
                    $actual = $actualRoots[$j];
                    $expected = $expectedRoots[$j];
                    while ($actual && $expected) {
                        $this->wptAssertEquals($actual->nodeType, $expected->nodeType, $msg . 'First difference: differing nodeType');
                        $this->wptAssertEquals($actual->nodeName, $expected->nodeName, $msg . 'First difference: differing nodeName');
                        $this->wptAssertEquals($actual->nodeValue, $expected->nodeValue, $msg . 'First difference: differing nodeValue (nodeName = "' . $actual->nodeName . '")');
                        $this->wptAssertEquals(count($actual->childNodes), count($expected->childNodes), $msg . 'First difference: differing number of children (nodeName = "' . $actual->nodeName . '")');
                        $actual = Common::nextNode($actual);
                        $expected = Common::nextNode($expected);
                    }
                    $this->wptAssertUnreached("DOMs were not equal but we couldn't figure out why");
                }
                if ($j == 0) {
                    // Clearly something is wrong if the node lists are different
                    // lengths.  We want to report this only after we've already
                    // checked the main tree for equality, though, so it doesn't
                    // mask more interesting errors.
                    $this->wptAssertEquals(count($actualRoots), count($expectedRoots), "Actual and expected DOMs were broken up into a different number of pieces by deleteContents() (this probably means you created or detached nodes when you weren't supposed to)");
                }
            }
        });
        $domTests[$i]->done();
        $positionTests[$i]->step(function () use (&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange, &$actualRoots, &$expectedRoots) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual deleteContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated deleteContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            $this->wptAssertTrue($actualRoots[0]->isEqualNode($expectedRoots[0]), 'The resulting DOMs were not equal, so comparing positions makes no sense');
            $this->wptAssertEquals($actualRange->startContainer, $actualRange->endContainer, 'startContainer and endContainer must always be the same after deleteContents()');
            $this->wptAssertEquals($actualRange->startOffset, $actualRange->endOffset, 'startOffset and endOffset must always be the same after deleteContents()');
            $this->wptAssertEquals($expectedRange->startContainer, $expectedRange->endContainer, 'Test bug!  Expected startContainer and endContainer must always be the same after deleteContents()');
            $this->wptAssertEquals($expectedRange->startOffset, $expectedRange->endOffset, 'Test bug!  Expected startOffset and endOffset must always be the same after deleteContents()');
            $this->wptAssertEquals($actualRange->startOffset, $expectedRange->startOffset, 'Unexpected startOffset after deleteContents()');
            // How do we decide that the two nodes are equal, since they're in
            // different trees?  Since the DOMs are the same, it's enough to check
            // that the index in the parent is the same all the way up the tree.
            // But we can first cheat by just checking they're actually equal.
            $this->wptAssertTrue($actualRange->startContainer->isEqualNode($expectedRange->startContainer), 'Unexpected startContainer after deleteContents(), expected ' . strtolower($expectedRange->startContainer->nodeName) . ' but got ' . strtolower($actualRange->startContainer->nodeName));
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
    }
    public function testRangeDeleteContents()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-deleteContents.html');
        $this->getCommon()->testDiv->parentNode->removeChild($this->getCommon()->testDiv);
        $actualIframe = $this->doc->createElement('iframe');
        $actualIframe->style->display = 'none';
        $this->doc->body->appendChild($actualIframe);
        $expectedIframe = $this->doc->createElement('iframe');
        $expectedIframe->style->display = 'none';
        $this->doc->body->appendChild($expectedIframe);
        // First test a detached Range, synchronously
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $range->detach();
            $range->deleteContents();
        }, 'Detached Range');
        $iStart = 0;
        $iStop = count($this->getCommon()->testRanges);
        if (preg_match('/subtest=[0-9]+/', $this->getLocation()->search)) {
            $matches = preg_match('/subtest=([0-9]+)/', $this->getLocation()->search, $FIXME);
            $iStart = intval($matches[1]);
            $iStop = intval($matches[1]) + 1;
        }
        $domTests = [];
        $positionTests = [];
        for ($i = $iStart; $i < $iStop; $i++) {
            $domTests[$i] = $this->asyncTest('Resulting DOM for range ' . $i . ' ' . $this->getCommon()->testRanges[$i]);
            $positionTests[$i] = $this->asyncTest('Resulting cursor position for range ' . $i . ' ' . $this->getCommon()->testRanges[$i]);
        }
        $referenceDoc = $this->doc->implementation->createHTMLDocument('');
        $referenceDoc->removeChild($referenceDoc->documentElement);
        $actualIframe->onload = function () use (&$expectedIframe, &$iStart, &$iStop, &$referenceDoc, &$actualIframe) {
            $expectedIframe->onload = function () use (&$iStart, &$iStop) {
                for ($i = $iStart; $i < $iStop; $i++) {
                    $this->helperTestDeleteContents($i);
                }
            };
            $expectedIframe->src = 'Range-test-iframe.html';
            $referenceDoc->appendChild($actualIframe->getOwnerDocument()->documentElement->cloneNode(true));
        };
        $actualIframe->src = 'Range-test-iframe.html';
    }
}
