<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-extractContents.html.
class RangeExtractContentsTest extends WPTTestHarness
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
        $iframe->contentWindow->testRangeInput = $testRanges[$i];
        $iframe->contentWindow->run();
    }
    public function testExtractContents($i)
    {
        global $actualIframe;
        global $expectedIframe;
        restoreIframe($actualIframe, $i);
        restoreIframe($expectedIframe, $i);
        $actualRange = $actualIframe->contentWindow->testRange;
        $expectedRange = $expectedIframe->contentWindow->testRange;
        $actualFrag = null;
        $expectedFrag = null;
        $actualRoots = null;
        $expectedRoots = null;
        $domTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange) {
            $this->assertEqualsData($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual extractContents()');
            $this->assertEqualsData($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated extractContents()');
            $this->assertEqualsData(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->assertEqualsData(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
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
            $node = $this->furthestAncestor($actualRange->startContainer);
            do {
                $actualAllNodes[] = $node;
            } while ($node = nextNode($node));
            $expectedAllNodes = [];
            $node = $this->furthestAncestor($expectedRange->startContainer);
            do {
                $expectedAllNodes[] = $node;
            } while ($node = nextNode($node));
            $expectedFrag = myExtractContents($expectedRange);
            if (gettype($expectedFrag) == 'string') {
                $this->assertThrowsDomData($expectedFrag, $actualIframe->contentWindow->DOMException, function () use(&$actualRange) {
                    $actualRange->extractContents();
                });
            } else {
                $actualFrag = $actualRange->extractContents();
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
                $this->assertNodesEqualData($actualRoots[$j], $expectedRoots[$j], $j ? 'detached node #' . $j : 'tree root');
                if ($j == 0) {
                    // Clearly something is wrong if the node lists are different
                    // lengths.  We want to report this only after we've already
                    // checked the main tree for equality, though, so it doesn't
                    // mask more interesting errors.
                    $this->assertEqualsData(count($actualRoots), count($expectedRoots), "Actual and expected DOMs were broken up into a different number of pieces by extractContents() (this probably means you created or detached nodes when you weren't supposed to)");
                }
            }
        });
        $domTests[$i]->done();
        $positionTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange, &$actualRoots, &$expectedRoots, &$expectedFrag) {
            $this->assertEqualsData($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual extractContents()');
            $this->assertEqualsData($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated extractContents()');
            $this->assertEqualsData(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->assertEqualsData(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            $this->assertTrueData($actualRoots[0]->isEqualNode($expectedRoots[0]), 'The resulting DOMs were not equal, so comparing positions makes no sense');
            if (gettype($expectedFrag) == 'string') {
                // It's no longer true that, e.g., startContainer and endContainer
                // must always be the same
                return;
            }
            $this->assertEqualsData($actualRange->startContainer, $actualRange->endContainer, 'startContainer and endContainer must always be the same after extractContents()');
            $this->assertEqualsData($actualRange->startOffset, $actualRange->endOffset, 'startOffset and endOffset must always be the same after extractContents()');
            $this->assertEqualsData($expectedRange->startContainer, $expectedRange->endContainer, 'Test bug!  Expected startContainer and endContainer must always be the same after extractContents()');
            $this->assertEqualsData($expectedRange->startOffset, $expectedRange->endOffset, 'Test bug!  Expected startOffset and endOffset must always be the same after extractContents()');
            $this->assertEqualsData($actualRange->startOffset, $expectedRange->startOffset, 'Unexpected startOffset after extractContents()');
            // How do we decide that the two nodes are equal, since they're in
            // different trees?  Since the DOMs are the same, it's enough to check
            // that the index in the parent is the same all the way up the tree.
            // But we can first cheat by just checking they're actually equal.
            $this->assertTrueData($actualRange->startContainer->isEqualNode($expectedRange->startContainer), 'Unexpected startContainer after extractContents(), expected ' . strtolower($expectedRange->startContainer->nodeName) . ' but got ' . strtolower($actualRange->startContainer->nodeName));
            $currentActual = $actualRange->startContainer;
            $currentExpected = $expectedRange->startContainer;
            $actual = '';
            $expected = '';
            while ($currentActual && $currentExpected) {
                $actual = indexOf($currentActual) . '-' . $actual;
                $expected = indexOf($currentExpected) . '-' . $expected;
                $currentActual = $currentActual->parentNode;
                $currentExpected = $currentExpected->parentNode;
            }
            $actual = substr($actual, 0, count($actual) - 1);
            $expected = substr($expected, 0, count($expected) - 1);
            $this->assertEqualsData($actual, $expected, "startContainer superficially looks right but is actually the wrong node if you trace back its index in all its ancestors (I'm surprised this actually happened");
        });
        $positionTests[$i]->done();
        $fragTests[$i]->step(function () use(&$actualIframe, &$expectedIframe, &$actualRange, &$expectedRange, &$expectedFrag, &$actualFrag) {
            $this->assertEqualsData($actualIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for actual extractContents()');
            $this->assertEqualsData($expectedIframe->contentWindow->unexpectedException, null, 'Unexpected exception thrown when setting up Range for simulated extractContents()');
            $this->assertEqualsData(gettype($actualRange), 'object', 'typeof Range produced in actual iframe');
            $this->assertEqualsData(gettype($expectedRange), 'object', 'typeof Range produced in expected iframe');
            if (gettype($expectedFrag) == 'string') {
                // Comparing makes no sense
                return;
            }
            $this->assertNodesEqualData($actualFrag, $expectedFrag, 'returned fragment');
        });
        $fragTests[$i]->done();
    }
    public function testRangeExtractContents()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-extractContents.html');
        $testDiv->parentNode->removeChild($testDiv);
        $actualIframe = $this->doc->createElement('iframe');
        $actualIframe->style->display = 'none';
        $this->getDocBody( $this->doc )->appendChild($actualIframe);
        $expectedIframe = $this->doc->createElement('iframe');
        $expectedIframe->style->display = 'none';
        $this->getDocBody( $this->doc )->appendChild($expectedIframe);
        // First test a detached Range, synchronously
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $range->detach();
            $this->assertArrayEqualsData($range->extractContents()->childNodes, []);
        }, 'Detached Range');
        $iStart = 0;
        $iStop = count($testRanges);
        if (preg_match('/subtest=[0-9]+/', $location->search)) {
            $matches = preg_match('/subtest=([0-9]+)/', $location->search, $FIXME);
            $iStart = intval($matches[1]);
            $iStop = intval($matches[1]) + 1;
        }
        $domTests = [];
        $positionTests = [];
        $fragTests = [];
        for ($i = $iStart; $i < $iStop; $i++) {
            $domTests[$i] = $this->asyncTest('Resulting DOM for range ' . $i . ' ' . $testRanges[$i]);
            $positionTests[$i] = $this->asyncTest('Resulting cursor position for range ' . $i . ' ' . $testRanges[$i]);
            $fragTests[$i] = $this->asyncTest('Returned fragment for range ' . $i . ' ' . $testRanges[$i]);
        }
        $referenceDoc = $this->doc->implementation->createHTMLDocument('');
        $referenceDoc->removeChild($referenceDoc->documentElement);
        $actualIframe->onload = function () use(&$expectedIframe, &$iStart, &$iStop, &$referenceDoc, &$actualIframe) {
            $expectedIframe->onload = function () use(&$iStart, &$iStop) {
                for ($i = $iStart; $i < $iStop; $i++) {
                    $this->testExtractContents($i);
                }
            };
            $expectedIframe->src = 'Range-test-iframe.html';
            $referenceDoc->appendChild($actualIframe->getOwnerDocument()->documentElement->cloneNode(true));
        };
        $actualIframe->src = 'Range-test-iframe.html';
    }
}
