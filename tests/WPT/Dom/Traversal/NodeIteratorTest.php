<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Traversal;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/NodeIterator.html.
class NodeIteratorTest extends WPTTestHarness
{
    public function checkIter($iter, $root, $whatToShowValue)
    {
        $whatToShowValue = $whatToShowValue === null ? 0xffffffff : $whatToShowValue;
        $this->wptAssertEquals($iter, '[object NodeIterator]', 'toString');
        $this->wptAssertEquals($iter->root, $root, 'root');
        $this->wptAssertEquals($iter->whatToShow, $whatToShowValue, 'whatToShow');
        $this->wptAssertEquals($iter->filter, null, 'filter');
        $this->wptAssertEquals($iter->referenceNode, $root, 'referenceNode');
        $this->wptAssertEquals($iter->pointerBeforeReferenceNode, true, 'pointerBeforeReferenceNode');
        $this->wptAssertReadonly($iter, 'root');
        $this->wptAssertReadonly($iter, 'whatToShow');
        $this->wptAssertReadonly($iter, 'filter');
        $this->wptAssertReadonly($iter, 'referenceNode');
        $this->wptAssertReadonly($iter, 'pointerBeforeReferenceNode');
    }
    public function helperTestIterator($root, $whatToShow, $filter)
    {
        $iter = $this->doc->createNodeIterator($root, $whatToShow, $filter);
        $this->wptAssertEquals($iter->root, $root, '.root');
        $this->wptAssertEquals($iter->referenceNode, $root, 'Initial .referenceNode');
        $this->wptAssertEquals($iter->pointerBeforeReferenceNode, true, '.pointerBeforeReferenceNode');
        $this->wptAssertEquals($iter->whatToShow, $whatToShow, '.whatToShow');
        $this->wptAssertEquals($iter->filter, $filter, '.filter');
        $expectedReferenceNode = $root;
        $expectedBeforeNode = true;
        // "Let node be the value of the referenceNode attribute."
        $node = $root;
        // "Let before node be the value of the pointerBeforeReferenceNode
        // attribute."
        $beforeNode = true;
        $i = 1;
        // Each loop iteration runs nextNode() once.
        while ($node) {
            do {
                if (!$beforeNode) {
                    // "If before node is false, let node be the first node following node
                    // in the iterator collection. If there is no such node return null."
                    $node = Common::nextNode($node);
                    if (!Common::isInclusiveDescendant($node, $root)) {
                        $node = null;
                        break;
                    }
                } else {
                    // "If before node is true, set it to false."
                    $beforeNode = false;
                }
                // "Filter node and let result be the return value.
                //
                // "If result is FILTER_ACCEPT, go to the next step in the overall set of
                // steps.
                //
                // "Otherwise, run these substeps again."
                if (!(1 << $node->nodeType - 1 & $whatToShow) || $filter && $filter($node) != NodeFilter::FILTER_ACCEPT) {
                    continue;
                }
                // "Set the referenceNode attribute to node, set the
                // pointerBeforeReferenceNode attribute to before node, and return node."
                $expectedReferenceNode = $node;
                $expectedBeforeNode = $beforeNode;
                break;
            } while (true);
            $this->wptAssertEquals($iter->nextNode(), $node, '.nextNode() ' . $i . ' time(s)');
            $this->wptAssertEquals($iter->referenceNode, $expectedReferenceNode, '.referenceNode after nextNode() ' . $i . ' time(s)');
            $this->wptAssertEquals($iter->pointerBeforeReferenceNode, $expectedBeforeNode, '.pointerBeforeReferenceNode after nextNode() ' . $i . ' time(s)');
            $i++;
        }
        // Same but for previousNode() (mostly copy-pasted, oh well)
        $iter = $this->doc->createNodeIterator($root, $whatToShow, $filter);
        $expectedReferenceNode = $root;
        $expectedBeforeNode = true;
        // "Let node be the value of the referenceNode attribute."
        $node = $root;
        // "Let before node be the value of the pointerBeforeReferenceNode
        // attribute."
        $beforeNode = true;
        $i = 1;
        // Each loop iteration runs previousNode() once.
        while ($node) {
            do {
                if ($beforeNode) {
                    // "If before node is true, let node be the first node preceding node
                    // in the iterator collection. If there is no such node return null."
                    $node = Common::previousNode($node);
                    if (!Common::isInclusiveDescendant($node, $root)) {
                        $node = null;
                        break;
                    }
                } else {
                    // "If before node is false, set it to true."
                    $beforeNode = true;
                }
                // "Filter node and let result be the return value.
                //
                // "If result is FILTER_ACCEPT, go to the next step in the overall set of
                // steps.
                //
                // "Otherwise, run these substeps again."
                if (!(1 << $node->nodeType - 1 & $whatToShow) || $filter && $filter($node) != NodeFilter::FILTER_ACCEPT) {
                    continue;
                }
                // "Set the referenceNode attribute to node, set the
                // pointerBeforeReferenceNode attribute to before node, and return node."
                $expectedReferenceNode = $node;
                $expectedBeforeNode = $beforeNode;
                break;
            } while (true);
            $this->wptAssertEquals($iter->previousNode(), $node, '.previousNode() ' . $i . ' time(s)');
            $this->wptAssertEquals($iter->referenceNode, $expectedReferenceNode, '.referenceNode after previousNode() ' . $i . ' time(s)');
            $this->wptAssertEquals($iter->pointerBeforeReferenceNode, $expectedBeforeNode, '.pointerBeforeReferenceNode after previousNode() ' . $i . ' time(s)');
            $i++;
        }
    }
    public function testNodeIterator()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/NodeIterator.html');
        $this->assertTest(function () {
            $iter = $this->doc->createNodeIterator($this->doc);
            $iter->detach();
            $iter->detach();
        }, 'detach() should be a no-op');
        $this->assertTest(function () {
            $iter = $this->doc->createNodeIterator($this->doc);
            $this->checkIter($iter, $this->doc);
        }, 'createNodeIterator() parameter defaults');
        $this->assertTest(function () {
            $iter = $this->doc->createNodeIterator($this->doc, null, null);
            $this->checkIter($iter, $this->doc, 0);
        }, 'createNodeIterator() with null as arguments');
        $this->assertTest(function () {
            $iter = $this->doc->createNodeIterator($this->doc, null, null);
            $this->checkIter($iter, $this->doc);
        }, 'createNodeIterator() with undefined as arguments');
        $this->assertTest(function () {
            $err = ['name' => 'failed'];
            $iter = $this->doc->createNodeIterator($this->doc, NodeFilter::SHOW_ALL, function () use(&$err) {
                throw $err;
            });
            $this->wptAssertThrowsExactly($err, function () use(&$iter) {
                $iter->nextNode();
            });
        }, 'Propagate exception from filter function');
        $this->assertTest(function () {
            $depth = 0;
            $iter = $this->doc->createNodeIterator($this->doc, NodeFilter::SHOW_ALL, function () use(&$iter, &$depth) {
                if ($iter->referenceNode != $this->doc && $depth == 0) {
                    $depth++;
                    $iter->nextNode();
                }
                return NodeFilter::FILTER_ACCEPT;
            });
            $iter->nextNode();
            $iter->nextNode();
            $this->wptAssertThrowsDom('InvalidStateError', function () use(&$iter) {
                $iter->nextNode();
            });
            $depth--;
            $this->wptAssertThrowsDom('InvalidStateError', function () use(&$iter) {
                $iter->previousNode();
            });
        }, 'Recursive filters need to throw');
        $whatToShows = ['0', '0xFFFFFFFF', 'NodeFilter.SHOW_ELEMENT', 'NodeFilter.SHOW_ATTRIBUTE', 'NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_DOCUMENT'];
        $callbacks = ['null', '(function(node) { return true })', '(function(node) { return false })', "(function(node) { return node.nodeName[0] == '#' })"];
        for ($i = 0; $i < count($this->getCommon()->testNodes); $i++) {
            for ($j = 0; $j < count($whatToShows); $j++) {
                for ($k = 0; $k < count($callbacks); $k++) {
                    $this->assertTest(function () use(&$i, &$whatToShows, &$j, &$callbacks, &$k) {
                        $this->helperTestIterator($this->wptEvalNode($this->getCommon()->testNodes[$i]), eval($whatToShows[$j]), eval($callbacks[$k]));
                    }, 'document.createNodeIterator(' . $this->getCommon()->testNodes[$i] . ', ' . $whatToShows[$j] . ', ' . $callbacks[$k] . ')');
                }
            }
        }
        $this->getCommon()->testDiv->style->display = 'none';
    }
}
