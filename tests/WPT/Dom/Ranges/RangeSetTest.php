<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-set.html.
class RangeSetTest extends WPTTestHarness
{
    public function testSetStart($range, $node, $offset)
    {
        if ($node->nodeType == Node::DOCUMENT_TYPE_NODE) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setStart($node, $offset);
            }, 'setStart() to a doctype must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        if ($offset < 0 || $offset > Common::nodeLength($node)) {
            $this->wptAssertThrowsDom('INDEX_SIZE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setStart($node, $offset);
            }, 'setStart() to a too-large offset must throw INDEX_SIZE_ERR');
            return;
        }
        $newRange = $range->cloneRange();
        $newRange->setStart($node, $offset);
        $this->wptAssertEquals($newRange->startContainer, $node, 'setStart() must change startContainer to the new node');
        $this->wptAssertEquals($newRange->startOffset, $offset, 'setStart() must change startOffset to the new offset');
        // FIXME: I'm assuming comparePoint() is correct, but the tests for that
        // will depend on setStart()/setEnd().
        if (Common::furthestAncestor($node) != Common::furthestAncestor($range->startContainer) || $range->comparePoint($node, $offset) > 0) {
            $this->wptAssertEquals($newRange->endContainer, $node, 'setStart(node, offset) where node is after current end or in different document must set the end node to node too');
            $this->wptAssertEquals($newRange->endOffset, $offset, 'setStart(node, offset) where node is after current end or in different document must set the end offset to offset too');
        } else {
            $this->wptAssertEquals($newRange->endContainer, $range->endContainer, 'setStart() must not change the end node if the new start is before the old end');
            $this->wptAssertEquals($newRange->endOffset, $range->endOffset, 'setStart() must not change the end offset if the new start is before the old end');
        }
    }
    public function testSetEnd($range, $node, $offset)
    {
        if ($node->nodeType == Node::DOCUMENT_TYPE_NODE) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setEnd($node, $offset);
            }, 'setEnd() to a doctype must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        if ($offset < 0 || $offset > Common::nodeLength($node)) {
            $this->wptAssertThrowsDom('INDEX_SIZE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setEnd($node, $offset);
            }, 'setEnd() to a too-large offset must throw INDEX_SIZE_ERR');
            return;
        }
        $newRange = $range->cloneRange();
        $newRange->setEnd($node, $offset);
        // FIXME: I'm assuming comparePoint() is correct, but the tests for that
        // will depend on setStart()/setEnd().
        if (Common::furthestAncestor($node) != Common::furthestAncestor($range->startContainer) || $range->comparePoint($node, $offset) < 0) {
            $this->wptAssertEquals($newRange->startContainer, $node, 'setEnd(node, offset) where node is before current start or in different document must set the end node to node too');
            $this->wptAssertEquals($newRange->startOffset, $offset, 'setEnd(node, offset) where node is before current start or in different document must set the end offset to offset too');
        } else {
            $this->wptAssertEquals($newRange->startContainer, $range->startContainer, 'setEnd() must not change the start node if the new end is after the old start');
            $this->wptAssertEquals($newRange->startOffset, $range->startOffset, 'setEnd() must not change the start offset if the new end is after the old start');
        }
        $this->wptAssertEquals($newRange->endContainer, $node, 'setEnd() must change endContainer to the new node');
        $this->wptAssertEquals($newRange->endOffset, $offset, 'setEnd() must change endOffset to the new offset');
    }
    public function testSetStartBefore($range, $node)
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->setStartBefore($node);
            }, 'setStartBefore() to a node with null parent must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        $idx = 0;
        while ($node->parentNode->childNodes[$idx] != $node) {
            $idx++;
        }
        $this->testSetStart($range, $node->parentNode, $idx);
    }
    public function testSetStartAfter($range, $node)
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->setStartAfter($node);
            }, 'setStartAfter() to a node with null parent must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        $idx = 0;
        while ($node->parentNode->childNodes[$idx] != $node) {
            $idx++;
        }
        $this->testSetStart($range, $node->parentNode, $idx + 1);
    }
    public function testSetEndBefore($range, $node)
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->setEndBefore($node);
            }, 'setEndBefore() to a node with null parent must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        $idx = 0;
        while ($node->parentNode->childNodes[$idx] != $node) {
            $idx++;
        }
        $this->testSetEnd($range, $node->parentNode, $idx);
    }
    public function testSetEndAfter($range, $node)
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->setEndAfter($node);
            }, 'setEndAfter() to a node with null parent must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        $idx = 0;
        while ($node->parentNode->childNodes[$idx] != $node) {
            $idx++;
        }
        $this->testSetEnd($range, $node->parentNode, $idx + 1);
    }
    public function testRangeSet()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-set.html');
        $startTests = [];
        $endTests = [];
        $startBeforeTests = [];
        $startAfterTests = [];
        $endBeforeTests = [];
        $endAfterTests = [];
        // Don't want to eval() each point a bazillion times
        $testPointsCached = $this->arrayMap($testPoints, $eval);
        $this->getCommon()->testNodesCached = $this->arrayMap($this->getCommon()->testNodesShort, $eval);
        for ($i = 0; $i < count($this->getCommon()->testRangesShort); $i++) {
            $endpoints = $this->wptEvalNode($this->getCommon()->testRangesShort[$i]);
            $range = null;
            $this->assertTest(function () use(&$endpoints) {
                $range = Common::ownerDocument($endpoints[0])->createRange();
                $range->setStart($endpoints[0], $endpoints[1]);
                $range->setEnd($endpoints[2], $endpoints[3]);
            }, 'Set up range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i]);
            for ($j = 0; $j < count($testPoints); $j++) {
                $startTests[] = ['setStart() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', point ' . $j . ' ' . $testPoints[$j], $range, $testPointsCached[$j][0], $testPointsCached[$j][1]];
                $endTests[] = ['setEnd() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', point ' . $j . ' ' . $testPoints[$j], $range, $testPointsCached[$j][0], $testPointsCached[$j][1]];
            }
            for ($j = 0; $j < count($this->getCommon()->testNodesShort); $j++) {
                $startBeforeTests[] = ['setStartBefore() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', node ' . $j . ' ' . $this->getCommon()->testNodesShort[$j], $range, $this->getCommon()->testNodesCached[$j]];
                $startAfterTests[] = ['setStartAfter() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', node ' . $j . ' ' . $this->getCommon()->testNodesShort[$j], $range, $this->getCommon()->testNodesCached[$j]];
                $endBeforeTests[] = ['setEndBefore() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', node ' . $j . ' ' . $this->getCommon()->testNodesShort[$j], $range, $this->getCommon()->testNodesCached[$j]];
                $endAfterTests[] = ['setEndAfter() with range ' . $i . ' ' . $this->getCommon()->testRangesShort[$i] . ', node ' . $j . ' ' . $this->getCommon()->testNodesShort[$j], $range, $this->getCommon()->testNodesCached[$j]];
            }
        }
        $this->generateTests($testSetStart, $startTests);
        $this->generateTests($testSetEnd, $endTests);
        $this->generateTests($testSetStartBefore, $startBeforeTests);
        $this->generateTests($testSetStartAfter, $startAfterTests);
        $this->generateTests($testSetEndBefore, $endBeforeTests);
        $this->generateTests($testSetEndAfter, $endAfterTests);
        $this->getCommon()->testDiv->style->display = 'none';
    }
}
