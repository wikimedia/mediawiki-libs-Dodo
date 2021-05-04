<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentType-remove.html.
class DocumentTypeRemoveTest extends WptTestHarness
{
    public function assertTestRemove($node, $parent, $type)
    {
        global $node;
        $this->assertTest(function () use(&$node) {
            $this->assertTrueData(isset($node['remove']));
            $this->assertEqualsData(gettype($node->remove), 'function');
            $this->assertEqualsData(count($node->remove), 0);
        }, $type . ' should support remove()');
        $this->assertTest(function () use(&$node) {
            $this->assertEqualsData($node->parentNode, null, 'Node should not have a parent');
            $this->assertEqualsData($node->remove(), null);
            $this->assertEqualsData($node->parentNode, null, 'Removed new node should not have a parent');
        }, 'remove() should work if ' . $type . " doesn't have a parent");
        $this->assertTest(function () use(&$node, &$parent) {
            $this->assertEqualsData($node->parentNode, null, 'Node should not have a parent');
            $parent->appendChild($node);
            $this->assertEqualsData($node->parentNode, $parent, 'Appended node should have a parent');
            $this->assertEqualsData($node->remove(), null);
            $this->assertEqualsData($node->parentNode, null, 'Removed node should not have a parent');
            $this->assertArrayEqualsData($parent->childNodes, [], 'Parent should not have children');
        }, 'remove() should work if ' . $type . ' does have a parent');
        $this->assertTest(function () use(&$node, &$parent) {
            $this->assertEqualsData($node->parentNode, null, 'Node should not have a parent');
            $before = $parent->appendChild($this->doc->createComment('before'));
            $parent->appendChild($node);
            $after = $parent->appendChild($this->doc->createComment('after'));
            $this->assertEqualsData($node->parentNode, $parent, 'Appended node should have a parent');
            $this->assertEqualsData($node->remove(), null);
            $this->assertEqualsData($node->parentNode, null, 'Removed node should not have a parent');
            $this->assertArrayEqualsData($parent->childNodes, [$before, $after], 'Parent should have two children left');
        }, 'remove() should work if ' . $type . ' does have a parent and siblings');
    }
    public function testDocumentTypeRemove()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentType-remove.html');
        $node = null;
        $parentNode = null;
        // setup()
        $node = $this->doc->implementation->createDocumentType('html', '', '');
        $parentNode = $this->doc->implementation->createDocument(null, '', null);
        $this->assertTestRemove($node, $parentNode, 'doctype');
    }
}
