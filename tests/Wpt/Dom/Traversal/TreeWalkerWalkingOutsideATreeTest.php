<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-walking-outside-a-tree.html.
class TreeWalkerWalkingOutsideATreeTest extends WptTestHarness
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
    public function testTreeWalkerWalkingOutsideATree()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-walking-outside-a-tree.html');
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
            $this->assertEqualsData($w->lastChild(), $p, 'TreeWalker failed after removing the current node from the tree');
            $doc->appendChild($p);
            $this->assertEqualsData($w->previousNode(), $title, 'failed to handle regrafting correctly');
            $p->appendChild($body);
            $this->assertEqualsData($w->nextNode(), $p, "couldn't retrace steps");
            $this->assertEqualsData($w->nextNode(), $body, "couldn't step back into root");
            $this->assertEqualsData($w->previousNode(), null, "root didn't retake its rootish position");
        }, 'walking outside a tree');
    }
}
