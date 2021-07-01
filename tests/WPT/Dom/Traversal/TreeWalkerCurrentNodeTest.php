<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-currentNode.html.
class TreeWalkerCurrentNodeTest extends WPTTestHarness
{
    public function assertNode($actual, $expected)
    {
        $this->wptAssertTrue($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType);
        if (gettype($expected->id) !== NULL) {
            $this->wptAssertEquals($actual->id, $expected->id);
        }
        if (gettype($expected->nodeValue) !== NULL) {
            $this->wptAssertEquals($actual->nodeValue, $expected->nodeValue);
        }
    }
    public function testTreeWalkerCurrentNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-currentNode.html');
        // var subTree = document.createElement('div');
        // subTree.innerHTML = "<p>Lorem ipsum <span>dolor <b>sit</b> amet</span>, consectetur <i>adipisicing</i> elit, sed do eiusmod <tt>tempor <b><i>incididunt ut</i> labore</b> et dolore magna</tt> aliqua.</p>"
        // document.body.appendChild(subTree);
        $subTree = $this->doc->getElementById('subTree');
        $all = function ($node) {
            return true;
        };
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT, $all);
            assert_node($w->currentNode, ['type' => Element, 'id' => 'subTree']);
            $this->wptAssertEquals($w->parentNode(), null);
            assert_node($w->currentNode, ['type' => Element, 'id' => 'subTree']);
        }, "Test that TreeWalker.parent() doesn't set the currentNode to a node not under the root.");
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT | NodeFilter::SHOW_COMMENT, $all);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->parentNode(), null);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->nextNode(), $this->doc->documentElement->firstChild);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement->firstChild);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->previousNode(), null);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->firstChild(), $this->doc->documentElement->firstChild);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement->firstChild);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->lastChild(), $this->doc->documentElement->lastChild);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement->lastChild);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->nextSibling(), null);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement);
            $w->currentNode = $this->doc->documentElement;
            $this->wptAssertEquals($w->getPreviousSibling()(), null);
            $this->wptAssertEquals($w->currentNode, $this->doc->documentElement);
        }, 'Test that we handle setting the currentNode to arbitrary nodes not under the root element.');
        $this->assertTest(function () use(&$subTree, &$all) {
            $w = $this->doc->createTreeWalker($subTree, NodeFilter::SHOW_ELEMENT, $all);
            $w->currentNode = $subTree->getPreviousSibling();
            $this->wptAssertEquals($w->nextNode(), $subTree);
            $w->currentNode = $this->doc->getElementById('parent');
            $this->wptAssertEquals($w->firstChild(), $subTree);
        }, 'Test how we handle the case when the traversed to node is within the root, but the currentElement is not.');
    }
}
