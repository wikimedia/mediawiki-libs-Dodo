<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-compareDocumentPosition.html.
class NodeCompareDocumentPositionTest extends WPTTestHarness
{
    public function testNodeCompareDocumentPosition()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-compareDocumentPosition.html');
        foreach ($this->testNodes as $referenceName) {
            $reference = eval($referenceName);
            foreach ($this->testNodes as $otherName) {
                $other = eval($otherName);
                $this->assertTest(function () use(&$reference, &$other) {
                    $result = $reference->compareDocumentPosition($other);
                    // "If other and reference are the same object, return zero and
                    // terminate these steps."
                    if ($other === $reference) {
                        $this->assertEqualsData($result, 0);
                        return;
                    }
                    // "If other and reference are not in the same tree, return the result of
                    // adding DOCUMENT_POSITION_DISCONNECTED,
                    // DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC, and either
                    // DOCUMENT_POSITION_PRECEDING or DOCUMENT_POSITION_FOLLOWING, with the
                    // constraint that this is to be consistent, together and terminate these
                    // steps."
                    if ($this->furthestAncestor($reference) !== $this->furthestAncestor($other)) {
                        // TODO: Test that it's consistent.
                        $this->assertInArrayData($result, [Node::DOCUMENT_POSITION_DISCONNECTED + Node::DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC + Node::DOCUMENT_POSITION_PRECEDING, Node::DOCUMENT_POSITION_DISCONNECTED + Node::DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC + Node::DOCUMENT_POSITION_FOLLOWING]);
                        return;
                    }
                    // "If other is an ancestor of reference, return the result of
                    // adding DOCUMENT_POSITION_CONTAINS to DOCUMENT_POSITION_PRECEDING
                    // and terminate these steps."
                    $ancestor = $reference->parentNode;
                    while ($ancestor && $ancestor !== $other) {
                        $ancestor = $ancestor->parentNode;
                    }
                    if ($ancestor === $other) {
                        $this->assertEqualsData($result, Node::DOCUMENT_POSITION_CONTAINS + Node::DOCUMENT_POSITION_PRECEDING);
                        return;
                    }
                    // "If other is a descendant of reference, return the result of adding
                    // DOCUMENT_POSITION_CONTAINED_BY to DOCUMENT_POSITION_FOLLOWING and
                    // terminate these steps."
                    $ancestor = $other->parentNode;
                    while ($ancestor && $ancestor !== $reference) {
                        $ancestor = $ancestor->parentNode;
                    }
                    if ($ancestor === $reference) {
                        $this->assertEqualsData($result, Node::DOCUMENT_POSITION_CONTAINED_BY + Node::DOCUMENT_POSITION_FOLLOWING);
                        return;
                    }
                    // "If other is preceding reference return DOCUMENT_POSITION_PRECEDING
                    // and terminate these steps."
                    $prev = $this->previousNode($reference);
                    while ($prev && $prev !== $other) {
                        $prev = $this->previousNode($prev);
                    }
                    if ($prev === $other) {
                        $this->assertEqualsData($result, Node::DOCUMENT_POSITION_PRECEDING);
                        return;
                    }
                    // "Return DOCUMENT_POSITION_FOLLOWING."
                    $this->assertEqualsData($result, Node::DOCUMENT_POSITION_FOLLOWING);
                }, $referenceName . '.compareDocumentPosition(' . $otherName . ')');
            }
        }
        $testDiv->parentNode->removeChild($testDiv);
    }
}
