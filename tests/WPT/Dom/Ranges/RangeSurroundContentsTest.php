<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-surroundContents.html.
class RangeSurroundContentsTest extends WPTTestHarness
{
    public function mySurroundContents($range, $newParent)
    {
        try {
            // "If a non-Text node is partially contained in the context object,
            // throw a "InvalidStateError" exception and terminate these steps."
            $node = $range->commonAncestorContainer;
            $stop = Common::nextNodeDescendants($node);
            for (; $node != $stop; $node = Common::nextNode($node)) {
                if ($node->nodeType != Node::TEXT_NODE && Common::isPartiallyContained($node, $range)) {
                    return 'INVALID_STATE_ERR';
                }
            }
            // "If newParent is a Document, DocumentType, or DocumentFragment node,
            // throw an "InvalidNodeTypeError" exception and terminate these
            // steps."
            if ($newParent->nodeType == Node::DOCUMENT_NODE || $newParent->nodeType == Node::DOCUMENT_TYPE_NODE || $newParent->nodeType == Node::DOCUMENT_FRAGMENT_NODE) {
                return 'INVALID_NODE_TYPE_ERR';
            }
            // "Call extractContents() on the context object, and let fragment be
            // the result."
            $fragment = Common::myExtractContents($range);
            if (gettype($fragment) == 'string') {
                return $fragment;
            }
            // "While newParent has children, remove its first child."
            while (count($newParent->childNodes)) {
                $newParent->removeChild($newParent->firstChild);
            }
            // "Call insertNode(newParent) on the context object."
            $ret = Common::myInsertNode($range, $newParent);
            if (gettype($ret) == 'string') {
                return $ret;
            }
            // "Call appendChild(fragment) on newParent."
            $newParent->appendChild($fragment);
            // "Call selectNode(newParent) on the context object."
            //
            // We just reimplement this in-place.
            if (!$newParent->parentNode) {
                return 'INVALID_NODE_TYPE_ERR';
            }
            $index = Common::indexOf($newParent);
            $range->setStart($newParent->parentNode, $index);
            $range->setEnd($newParent->parentNode, $index + 1);
        } catch (Exception $e) {
            return Common::getDomExceptionName($e);
        }
    }
    public function restoreIframe($iframe, $i, $j)
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
        $iframe->contentWindow->testRangeInput = $this->testRangesShort[$i];
        $iframe->contentWindow->testNodeInput = $this->testNodesShort[$j];
        $iframe->contentWindow->run();
    }
    public function testSurroundContents($i, $j)
    {
        $actualRange = null;
        $expectedRange = null;
        $actualNode = null;
        $expectedNode = null;
        $actualRoots = [];
        $expectedRoots = [];
        $domTests[$i][$j]->step(function () use(&$i, &$j, &$actualRoots, &$expectedRoots) {
            $this->restoreIframe($actualIframe, $i, $j);
            $this->restoreIframe($expectedIframe, $i, $j);
            $actualRange = $actualIframe->contentWindow->testRange;
            $expectedRange = $expectedIframe->contentWindow->testRange;
            $actualNode = $actualIframe->contentWindow->testNode;
            $expectedNode = $expectedIframe->contentWindow->testNode;
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual surroundContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated surroundContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertNotEquals($actualRange, null, 'Range produced in actual iframe was null');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            $this->wptAssertNotEquals($expectedRange, null, 'Range produced in expected iframe was null');
            $this->wptAssertEquals(gettype($actualNode), 'object', 'typeof Node produced in actual iframe');
            $this->wptAssertNotEquals($actualNode, null, 'Node produced in actual iframe was null');
            $this->wptAssertEquals(gettype($expectedNode), 'object', 'typeof Node produced in expected iframe');
            $this->wptAssertNotEquals($expectedNode, null, 'Node produced in expected iframe was null');
            // We want to test that the trees containing the ranges are equal, and
            // also the trees containing the moved nodes.  These might not be the
            // same, if we're inserting a node from a detached tree or a different
            // document.
            $actualRoots[] = Common::furthestAncestor($actualRange->startContainer);
            $expectedRoots[] = Common::furthestAncestor($expectedRange->startContainer);
            if (Common::furthestAncestor($actualNode) != $actualRoots[0]) {
                $actualRoots[] = Common::furthestAncestor($actualNode);
            }
            if (Common::furthestAncestor($expectedNode) != $expectedRoots[0]) {
                $expectedRoots[] = Common::furthestAncestor($expectedNode);
            }
            $this->wptAssertEquals(count($actualRoots), count($expectedRoots), 'Either the actual node and actual range are in the same tree but the expected are in different trees, or vice versa');
            // This doctype stuff is to work around the fact that Opera 11.00 will
            // move around doctypes within a document, even to totally invalid
            // positions, but it won't allow a new doctype to be added to a
            // document in any way I can figure out.  So if we try moving a doctype
            // to some invalid place, in Opera it will actually succeed, and then
            // restoreIframe() will remove the doctype along with the root element,
            // and then nothing can re-add the doctype.  So instead, we catch it
            // during the test itself and move it back to the right place while we
            // still can.
            //
            // I spent *way* too much time debugging and working around this bug.
            $actualDoctype = $actualIframe->getOwnerDocument()->doctype;
            $expectedDoctype = $expectedIframe->getOwnerDocument()->doctype;
            $result = null;
            try {
                $result = mySurroundContents($expectedRange, $expectedNode);
            } catch (Exception $e) {
                if ($expectedDoctype != $expectedIframe->getOwnerDocument()->firstChild) {
                    $expectedIframe->getOwnerDocument()->insertBefore($expectedDoctype, $expectedIframe->getOwnerDocument()->firstChild);
                }
                throw $e;
            }
            if (gettype($result) == 'string') {
                $this->wptAssertThrowsDom($result, $actualIframe->contentWindow->DOMException, function () use(&$actualRange, &$actualNode, &$expectedDoctype, &$actualDoctype) {
                    try {
                        $actualRange->surroundContents($actualNode);
                    } catch (Exception $e) {
                        if ($expectedDoctype != $expectedIframe->getOwnerDocument()->firstChild) {
                            $expectedIframe->getOwnerDocument()->insertBefore($expectedDoctype, $expectedIframe->getOwnerDocument()->firstChild);
                        }
                        if ($actualDoctype != $actualIframe->getOwnerDocument()->firstChild) {
                            $actualIframe->getOwnerDocument()->insertBefore($actualDoctype, $actualIframe->getOwnerDocument()->firstChild);
                        }
                        throw $e;
                    }
                }, 'A ' . $result . ' must be thrown in this case');
                // Don't return, we still need to test DOM equality
            } else {
                try {
                    $actualRange->surroundContents($actualNode);
                } catch (Exception $e) {
                    if ($expectedDoctype != $expectedIframe->getOwnerDocument()->firstChild) {
                        $expectedIframe->getOwnerDocument()->insertBefore($expectedDoctype, $expectedIframe->getOwnerDocument()->firstChild);
                    }
                    if ($actualDoctype != $actualIframe->getOwnerDocument()->firstChild) {
                        $actualIframe->getOwnerDocument()->insertBefore($actualDoctype, $actualIframe->getOwnerDocument()->firstChild);
                    }
                    throw $e;
                }
            }
            for ($k = 0; $k < count($actualRoots); $k++) {
                Common::assertNodesEqual($actualRoots[$k], $expectedRoots[$k], $k ? "moved node's tree root" : "range's tree root");
            }
        });
        $domTests[$i][$j]->done();
        $positionTests[$i][$j]->step(function () use(&$actualRange, &$expectedRange, &$actualNode, &$expectedNode, &$actualRoots, &$expectedRoots) {
            $this->wptAssertEquals($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual surroundContents()');
            $this->wptAssertEquals($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated surroundContents()');
            $this->wptAssertEquals(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->wptAssertNotEquals($actualRange, null, 'Range produced in actual iframe was null');
            $this->wptAssertEquals(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            $this->wptAssertNotEquals($expectedRange, null, 'Range produced in expected iframe was null');
            $this->wptAssertEquals(gettype($actualNode), 'object', 'typeof Node produced in actual iframe');
            $this->wptAssertNotEquals($actualNode, null, 'Node produced in actual iframe was null');
            $this->wptAssertEquals(gettype($expectedNode), 'object', 'typeof Node produced in expected iframe');
            $this->wptAssertNotEquals($expectedNode, null, 'Node produced in expected iframe was null');
            for ($k = 0; $k < count($actualRoots); $k++) {
                Common::assertNodesEqual($actualRoots[$k], $expectedRoots[$k], $k ? "moved node's tree root" : "range's tree root");
            }
            $this->wptAssertEquals($actualRange->startOffset, $expectedRange->startOffset, 'Unexpected startOffset after surroundContents()');
            $this->wptAssertEquals($actualRange->endOffset, $expectedRange->endOffset, 'Unexpected endOffset after surroundContents()');
            // How do we decide that the two nodes are equal, since they're in
            // different trees?  Since the DOMs are the same, it's enough to check
            // that the index in the parent is the same all the way up the tree.
            // But we can first cheat by just checking they're actually equal.
            $this->wptAssertTrue($actualRange->startContainer->isEqualNode($expectedRange->startContainer), 'Unexpected startContainer after surroundContents(), expected ' . strtolower($expectedRange->startContainer->nodeName) . ' but got ' . strtolower($actualRange->startContainer->nodeName));
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
        $positionTests[$i][$j]->done();
    }
    public function testRangeSurroundContents()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-surroundContents.html');
        $testDiv->parentNode->removeChild($testDiv);
        $iStart = 0;
        $iStop = count($this->testRangesShort);
        $jStart = 0;
        $jStop = count($this->testNodesShort);
        if (preg_match('/subtest=[0-9]+,[0-9]+/', $location->search)) {
            $matches = preg_match('/subtest=([0-9]+),([0-9]+)/', $location->search, $FIXME);
            $iStart = intval($matches[1]);
            $iStop = intval($matches[1]) + 1;
            $jStart = intval($matches[2]) + 0;
            $jStop = intval($matches[2]) + 1;
        }
        $domTests = [];
        $positionTests = [];
        for ($i = $iStart; $i < $iStop; $i++) {
            $domTests[$i] = [];
            $positionTests[$i] = [];
            for ($j = $jStart; $j < $jStop; $j++) {
                $domTests[$i][$j] = $this->asyncTest($i . ',' . $j . ': resulting DOM for range ' . $this->testRangesShort[$i] . ', node ' . $this->testNodesShort[$j]);
                $positionTests[$i][$j] = $this->asyncTest($i . ',' . $j . ': resulting range position for range ' . $this->testRangesShort[$i] . ', node ' . $this->testNodesShort[$j]);
            }
        }
        $actualIframe = $this->doc->createElement('iframe');
        $actualIframe->style->display = 'none';
        $actualIframe->id = 'actual';
        $this->doc->body->appendChild($actualIframe);
        $expectedIframe = $this->doc->createElement('iframe');
        $expectedIframe->style->display = 'none';
        $expectedIframe->id = 'expected';
        $this->doc->body->appendChild($expectedIframe);
        $referenceDoc = $this->doc->implementation->createHTMLDocument('');
        $referenceDoc->removeChild($referenceDoc->documentElement);
        $actualIframe->onload = function () use(&$expectedIframe, &$iStart, &$iStop, &$jStart, &$jStop, &$referenceDoc, &$actualIframe) {
            $expectedIframe->onload = function () use(&$iStart, &$iStop, &$jStart, &$jStop) {
                for ($i = $iStart; $i < $iStop; $i++) {
                    for ($j = $jStart; $j < $jStop; $j++) {
                        $this->testSurroundContents($i, $j);
                    }
                }
            };
            $expectedIframe->src = 'Range-test-iframe.html';
            $referenceDoc->appendChild($actualIframe->getOwnerDocument()->documentElement->cloneNode(true));
        };
        $actualIframe->src = 'Range-test-iframe.html';
    }
}
