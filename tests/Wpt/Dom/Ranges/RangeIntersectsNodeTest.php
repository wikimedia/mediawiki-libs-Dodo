<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode.html.
class RangeIntersectsNodeTest extends WptTestHarness
{
    public function testRangeIntersectsNode()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode.html');
        // Will be filled in on the first run for that range
        $testRangesCached = [];
        for ($i = 0; $i < count($this->testNodes); $i++) {
            $node = eval($this->testNodes[$i]);
            for ($j = 0; $j < count($testRanges); $j++) {
                $this->assertTest(function () use(&$testRangesCached, &$j, &$i, &$node) {
                    if ($testRangesCached[$j] === null) {
                        try {
                            $testRangesCached[$j] = rangeFromEndpoints(eval($testRanges[$i]));
                        } catch (Exception $e) {
                            $testRangesCached[$j] = null;
                        }
                    }
                    $this->assertNotEqualsData($testRangesCached[$j], null, 'Setting up the range failed');
                    $range = $testRangesCached[$j]->cloneRange();
                    // "If node's root is different from the context object's root,
                    // return false and terminate these steps."
                    if (furthestAncestor($node) !== furthestAncestor($range->startContainer)) {
                        $this->assertEqualsData($range->intersectsNode($node), false, 'Must return false if node and range have different roots');
                        return;
                    }
                    // "Let parent be node's parent."
                    $parent_ = $node->parentNode;
                    // "If parent is null, return true and terminate these steps."
                    if (!$parent_) {
                        $this->assertEqualsData($range->intersectsNode($node), true, "Must return true if node's parent is null");
                        return;
                    }
                    // "Let offset be node's index."
                    $offset = indexOf($node);
                    // "If (parent, offset) is before end and (parent, offset + 1) is
                    // after start, return true and terminate these steps."
                    if (getPosition($parent_, $offset, $range->endContainer, $range->endOffset) === 'before' && getPosition($parent_, $offset + 1, $range->startContainer, $range->startOffset) === 'after') {
                        $this->assertEqualsData($range->intersectsNode($node), true, 'Must return true if (parent, offset) is before range end and (parent, offset + 1) is after range start');
                        return;
                    }
                    // "Return false."
                    $this->assertEqualsData($range->intersectsNode($node), false, 'Must return false if (parent, offset) is not before range end or (parent, offset + 1) is not after range start');
                }, 'Node ' . $i . ' ' . $this->testNodes[$i] . ', range ' . $j . ' ' . $testRanges[$j]);
            }
        }
        $testDiv->style->display = 'none';
    }
}