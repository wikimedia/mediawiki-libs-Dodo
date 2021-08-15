<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-remove.html.
class ElementRemoveTest extends WPTTestHarness
{
    public function helperTestRemove($node, $parent, $type)
    {
        global $node;
        $this->assertTest(function () use(&$node) {
            $this->wptAssertTrue(isset($node['remove']));
            $this->wptAssertEquals(gettype($node->remove), 'function');
            $this->wptAssertEquals(count($node->remove), 0);
        }, $type . ' should support remove()');
        $this->assertTest(function () use(&$node) {
            $this->wptAssertEquals($node->parentNode, null, 'Node should not have a parent');
            $this->wptAssertEquals($node->remove(), null);
            $this->wptAssertEquals($node->parentNode, null, 'Removed new node should not have a parent');
        }, 'remove() should work if ' . $type . " doesn't have a parent");
        $this->assertTest(function () use(&$node, &$parent) {
            $this->wptAssertEquals($node->parentNode, null, 'Node should not have a parent');
            $parent->appendChild($node);
            $this->wptAssertEquals($node->parentNode, $parent, 'Appended node should have a parent');
            $this->wptAssertEquals($node->remove(), null);
            $this->wptAssertEquals($node->parentNode, null, 'Removed node should not have a parent');
            $this->wptAssertArrayEquals($parent->childNodes, [], 'Parent should not have children');
        }, 'remove() should work if ' . $type . ' does have a parent');
        $this->assertTest(function () use(&$node, &$parent) {
            $this->wptAssertEquals($node->parentNode, null, 'Node should not have a parent');
            $before = $parent->appendChild($this->doc->createComment('before'));
            $parent->appendChild($node);
            $after = $parent->appendChild($this->doc->createComment('after'));
            $this->wptAssertEquals($node->parentNode, $parent, 'Appended node should have a parent');
            $this->wptAssertEquals($node->remove(), null);
            $this->wptAssertEquals($node->parentNode, null, 'Removed node should not have a parent');
            $this->wptAssertArrayEquals($parent->childNodes, [$before, $after], 'Parent should have two children left');
        }, 'remove() should work if ' . $type . ' does have a parent and siblings');
    }
    public function testElementRemove()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-remove.html');
        $node = null;
        $parentNode = null;
        // setup()
        $node = $this->doc->createElement('div');
        $parentNode = $this->doc->createElement('div');
        $this->helperTestRemove($node, $parentNode, 'element');
    }
}
