<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/NodeIterator-removal.html.
class NodeIteratorRemovalTest extends WptTestHarness
{
    public function testNodeIteratorRemoval()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/NodeIterator-removal.html');
        for ($i = 0; $i < count($this->testNodes); $i++) {
            $node = eval($this->testNodes[$i]);
            if (!$node->parentNode) {
                // Nothing to test
                continue;
            }
            $this->assertTest(function () use(&$node) {
                $iters = [];
                $descs = [];
                $expectedReferenceNodes = [];
                $expectedPointers = [];
                for ($j = 0; $j < count($this->testNodes); $j++) {
                    $root = eval($this->testNodes[$j]);
                    // Add all distinct iterators with this root, calling nextNode()
                    // repeatedly until it winds up with the same iterator.
                    for ($k = 0; true; $k++) {
                        $iter = $this->doc->createNodeIterator($root);
                        for ($l = 0; $l < $k; $l++) {
                            $iter->nextNode();
                        }
                        if ($k && $iter->referenceNode == $iters[count($iters) - 1]->referenceNode && $iter->pointerBeforeReferenceNode == $iters[count($iters) - 1]->pointerBeforeReferenceNode) {
                            break;
                        } else {
                            $iters[] = $iter;
                            $descs[] = 'document.createNodeIterator(' . $this->testNodes[$j] . ') advanced ' . $k . ' times';
                            $expectedReferenceNodes[] = $iter->referenceNode;
                            $expectedPointers[] = $iter->pointerBeforeReferenceNode;
                            $idx = count($iters) - 1;
                            // "If the node is root or is not an inclusive ancestor of the
                            // referenceNode attribute value, terminate these steps."
                            //
                            // We also have to rule out the case where node is an ancestor of
                            // root, which is implicitly handled by the spec since such a node
                            // was not part of the iterator collection to start with.
                            if (isInclusiveAncestor($node, $root) || !isInclusiveAncestor($node, $iter->referenceNode)) {
                                continue;
                            }
                            // "If the pointerBeforeReferenceNode attribute value is false, set
                            // the referenceNode attribute to the first node preceding the node
                            // that is being removed, and terminate these steps."
                            if (!$iter->pointerBeforeReferenceNode) {
                                $expectedReferenceNodes[$idx] = $this->previousNode($node);
                                continue;
                            }
                            // "If there is a node following the last inclusive descendant of the
                            // node that is being removed, set the referenceNode attribute to the
                            // first such node, and terminate these steps."
                            $next = nextNodeDescendants($node);
                            if ($next) {
                                $expectedReferenceNodes[$idx] = $next;
                                continue;
                            }
                            // "Set the referenceNode attribute to the first node preceding the
                            // node that is being removed and set the pointerBeforeReferenceNode
                            // attribute to false."
                            $expectedReferenceNodes[$idx] = $this->previousNode($node);
                            $expectedPointers[$idx] = false;
                        }
                    }
                }
                $oldParent = $node->parentNode;
                $oldSibling = $node->nextSibling;
                $oldParent->removeChild($node);
                for ($j = 0; $j < count($iters); $j++) {
                    $iter = $iters[$j];
                    $this->assertEqualsData($iter->referenceNode, $expectedReferenceNodes[$j], '.referenceNode of ' . $descs[$j]);
                    $this->assertEqualsData($iter->pointerBeforeReferenceNode, $expectedPointers[$j], '.pointerBeforeReferenceNode of ' . $descs[$j]);
                }
                $oldParent->insertBefore($node, $oldSibling);
            }, 'Test removing node ' . $this->testNodes[$i]);
        }
        $testDiv->style->display = 'none';
    }
}
