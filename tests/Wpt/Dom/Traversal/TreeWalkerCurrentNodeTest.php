<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-currentNode.html.
class TreeWalkerCurrentNodeTest extends WptTestHarness
{
    public function assertNode($actual, $expected)
    {
        $this->assertTrueData($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType);
        if (gettype($expected->id) !== NULL) {
            $this->assertEqualsData($actual->id, $expected->id);
        }
        if (gettype($expected->nodeValue) !== NULL) {
            $this->assertEqualsData($actual->nodeValue, $expected->nodeValue);
        }
    }
    public function testTreeWalkerCurrentNode()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-currentNode.html';
        // var subTree = document.createElement('div');
        // subTree.innerHTML = "<p>Lorem ipsum <span>dolor <b>sit</b> amet</span>, consectetur <i>adipisicing</i> elit, sed do eiusmod <tt>tempor <b><i>incididunt ut</i> labore</b> et dolore magna</tt> aliqua.</p>"
        // document.body.appendChild(subTree);
        $subTree = $this->doc->getElementById('subTree');
        $all = function ($node) {
            return true;
        };
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT, $all);
            $this->assertNodeData($w->currentNode, ['type' => Element, 'id' => 'subTree']);
            $this->assertEqualsData($w->parentNode(), null);
            $this->assertNodeData($w->currentNode, ['type' => Element, 'id' => 'subTree']);
        }, "Test that TreeWalker.parent() doesn't set the currentNode to a node not under the root.");
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT | NodeFilter::SHOW_COMMENT, $all);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->parentNode(), null);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->nextNode(), $this->doc->documentElement->firstChild);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement->firstChild);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->previousNode(), null);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->firstChild(), $this->doc->documentElement->firstChild);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement->firstChild);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->lastChild(), $this->doc->documentElement->lastChild);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement->lastChild);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->nextSibling(), null);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->assertEqualsData($w->previousSibling(), null);
            $this->assertEqualsData($w->currentNode, $this->doc->documentElement);
        }, 'Test that we handle setting the currentNode to arbitrary nodes not under the root element.');
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT, $all);
            $w->currentNode = $subTree->previousSibling;
            $this->assertEqualsData($w->nextNode(), $subTree);
            $w->currentNode = $this->doc->getElementById('parent');
            $this->assertEqualsData($w->firstChild(), $subTree);
        }, 'Test how we handle the case when the traversed to node is within the root, but the currentElement is not.');
    }
}
