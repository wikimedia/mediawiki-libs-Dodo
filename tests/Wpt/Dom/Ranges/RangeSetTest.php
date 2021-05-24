<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\IDLeDOM\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-set.html.
class RangeSetTest extends WptTestHarness
{
    public function testSetStart($range, $node, $offset)
    {
        if ($node->nodeType == Node::DOCUMENT_TYPE_NODE) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setStart($node, $offset);
            }, 'setStart() to a doctype must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        if ($offset < 0 || $offset > nodeLength($node)) {
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setStart($node, $offset);
            }, 'setStart() to a too-large offset must throw INDEX_SIZE_ERR');
            return;
        }
        $newRange = $range->cloneRange();
        $newRange->setStart($node, $offset);
        $this->assertEqualsData($newRange->startContainer, $node, 'setStart() must change startContainer to the new node');
        $this->assertEqualsData($newRange->startOffset, $offset, 'setStart() must change startOffset to the new offset');
        // FIXME: I'm assuming comparePoint() is correct, but the tests for that
        // will depend on setStart()/setEnd().
        if ($this->furthestAncestor($node) != $this->furthestAncestor($range->startContainer) || $range->comparePoint($node, $offset) > 0) {
            $this->assertEqualsData($newRange->endContainer, $node, 'setStart(node, offset) where node is after current end or in different document must set the end node to node too');
            $this->assertEqualsData($newRange->endOffset, $offset, 'setStart(node, offset) where node is after current end or in different document must set the end offset to offset too');
        } else {
            $this->assertEqualsData($newRange->endContainer, $range->endContainer, 'setStart() must not change the end node if the new start is before the old end');
            $this->assertEqualsData($newRange->endOffset, $range->endOffset, 'setStart() must not change the end offset if the new start is before the old end');
        }
    }
    public function testSetEnd($range, $node, $offset)
    {
        if ($node->nodeType == Node::DOCUMENT_TYPE_NODE) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setEnd($node, $offset);
            }, 'setEnd() to a doctype must throw INVALID_NODE_TYPE_ERR');
            return;
        }
        if ($offset < 0 || $offset > nodeLength($node)) {
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$range, &$node, &$offset) {
                $range->setEnd($node, $offset);
            }, 'setEnd() to a too-large offset must throw INDEX_SIZE_ERR');
            return;
        }
        $newRange = $range->cloneRange();
        $newRange->setEnd($node, $offset);
        // FIXME: I'm assuming comparePoint() is correct, but the tests for that
        // will depend on setStart()/setEnd().
        if ($this->furthestAncestor($node) != $this->furthestAncestor($range->startContainer) || $range->comparePoint($node, $offset) < 0) {
            $this->assertEqualsData($newRange->startContainer, $node, 'setEnd(node, offset) where node is before current start or in different document must set the end node to node too');
            $this->assertEqualsData($newRange->startOffset, $offset, 'setEnd(node, offset) where node is before current start or in different document must set the end offset to offset too');
        } else {
            $this->assertEqualsData($newRange->startContainer, $range->startContainer, 'setEnd() must not change the start node if the new end is after the old start');
            $this->assertEqualsData($newRange->startOffset, $range->startOffset, 'setEnd() must not change the start offset if the new end is after the old start');
        }
        $this->assertEqualsData($newRange->endContainer, $node, 'setEnd() must change endContainer to the new node');
        $this->assertEqualsData($newRange->endOffset, $offset, 'setEnd() must change endOffset to the new offset');
    }
    public function testSetStartBefore($range, $node)
    {
        $parent = $node->parentNode;
        if ($parent === null) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
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
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
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
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
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
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
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
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-set.html');
        $startTests = [];
        $endTests = [];
        $startBeforeTests = [];
        $startAfterTests = [];
        $endBeforeTests = [];
        $endAfterTests = [];
        // Don't want to eval() each point a bazillion times
        $testPointsCached = $this->arrayMap($testPoints, $eval);
        $this->testNodesCached = $this->arrayMap($this->testNodesShort, $eval);
        for ($i = 0; $i < count($this->testRangesShort); $i++) {
            $endpoints = eval($this->testRangesShort[$i]);
            $range = null;
            $this->assertTest(function () use(&$endpoints) {
                $range = ownerDocument($endpoints[0])->createRange();
                $range->setStart($endpoints[0], $endpoints[1]);
                $range->setEnd($endpoints[2], $endpoints[3]);
            }, 'Set up range ' . $i . ' ' . $this->testRangesShort[$i]);
            for ($j = 0; $j < count($testPoints); $j++) {
                $startTests[] = ['setStart() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', point ' . $j . ' ' . $testPoints[$j], $range, $testPointsCached[$j][0], $testPointsCached[$j][1]];
                $endTests[] = ['setEnd() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', point ' . $j . ' ' . $testPoints[$j], $range, $testPointsCached[$j][0], $testPointsCached[$j][1]];
            }
            for ($j = 0; $j < count($this->testNodesShort); $j++) {
                $startBeforeTests[] = ['setStartBefore() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', node ' . $j . ' ' . $this->testNodesShort[$j], $range, $this->testNodesCached[$j]];
                $startAfterTests[] = ['setStartAfter() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', node ' . $j . ' ' . $this->testNodesShort[$j], $range, $this->testNodesCached[$j]];
                $endBeforeTests[] = ['setEndBefore() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', node ' . $j . ' ' . $this->testNodesShort[$j], $range, $this->testNodesCached[$j]];
                $endAfterTests[] = ['setEndAfter() with range ' . $i . ' ' . $this->testRangesShort[$i] . ', node ' . $j . ' ' . $this->testNodesShort[$j], $range, $this->testNodesCached[$j]];
            }
        }
        $this->generateTests($testSetStart, $startTests);
        $this->generateTests($testSetEnd, $endTests);
        $this->generateTests($testSetStartBefore, $startBeforeTests);
        $this->generateTests($testSetStartAfter, $startAfterTests);
        $this->generateTests($testSetEndBefore, $endBeforeTests);
        $this->generateTests($testSetEndAfter, $endAfterTests);
        $testDiv->style->display = 'none';
    }
}
