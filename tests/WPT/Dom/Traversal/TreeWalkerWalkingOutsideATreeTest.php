<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Traversal;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-walking-outside-a-tree.html.
class TreeWalkerWalkingOutsideATreeTest extends WPTTestHarness
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
    public function testTreeWalkerWalkingOutsideATree()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-walking-outside-a-tree.html');
        $this->assertTest(function () {
            // test 6: walking outside a tree
            $doc = $this->doc->createElement('div');
            $head = $this->doc->createElement('head');
            $title = $this->doc->createElement('title');
            $body = $this->doc->createElement('body');
            $p = $this->doc->createElement('p');
            $doc->appendChild($head);
            $head->appendChild($title);
            $doc->appendChild($body);
            $body->appendChild($p);
            $w = $this->doc->createTreeWalker($body, 0xffffffff, null);
            $doc->removeChild($body);
            $this->wptAssertEquals($w->lastChild(), $p, 'TreeWalker failed after removing the current node from the tree');
            $doc->appendChild($p);
            $this->wptAssertEquals($w->previousNode(), $title, 'failed to handle regrafting correctly');
            $p->appendChild($body);
            $this->wptAssertEquals($w->nextNode(), $p, "couldn't retrace steps");
            $this->wptAssertEquals($w->nextNode(), $body, "couldn't step back into root");
            $this->wptAssertEquals($w->previousNode(), null, "root didn't retake its rootish position");
        }, 'walking outside a tree');
    }
}
