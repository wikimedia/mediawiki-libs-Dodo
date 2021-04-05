<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-appendChild.html.
class NodeAppendChildTest extends WptTestHarness
{
    public function testLeaf($node, $desc)
    {
        // WebIDL.
        $this->assertTest(function () use(&$node) {
            $this->assertThrowsJsData($this->type_error, function () use(&$node) {
                $node->appendChild(null);
            });
        }, 'Appending null to a ' . $desc);
        // Pre-insert step 1.
        $this->assertTest(function () use(&$node) {
            $this->assertThrowsDomData('HIERARCHY_REQUEST_ERR', function () use(&$node) {
                $node->appendChild($this->doc->createTextNode('fail'));
            });
        }, 'Appending to a ' . $desc);
    }
    public function testNodeAppendChild()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Node-appendChild.html';
        // WebIDL.
        $this->assertTest(function () {
            $this->assertThrowsJsData($this->type_error, function () {
                $this->doc->body->appendChild(null);
            });
            $this->assertThrowsJsData($this->type_error, function () {
                $this->doc->body->appendChild(['a' => 'b']);
            });
        }, 'WebIDL tests');
        // WebIDL and pre-insert step 1.
        $this->assertTest(function () {
            $this->testLeaf($this->doc->createTextNode('Foo'), 'text node');
            $this->testLeaf($this->doc->createComment('Foo'), 'comment');
            $this->testLeaf($this->doc->doctype, 'doctype');
        }, 'Appending to a leaf node.');
        // Pre-insert step 5.
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $this->assertThrowsDomData('HIERARCHY_REQUEST_ERR', function () use(&$frameDoc) {
                $this->doc->body->appendChild($frameDoc);
            });
        }, 'Appending a document');
        // Pre-insert step 8.
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $s = $frameDoc->createElement('a');
            $this->assertEqualsData($s->ownerDocument, $frameDoc);
            $this->doc->body->appendChild($s);
            $this->assertEqualsData($s->ownerDocument, $this->doc);
        }, 'Adopting an orphan');
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $s = $frameDoc->createElement('b');
            $this->assertEqualsData($s->ownerDocument, $frameDoc);
            $frameDoc->body->appendChild($s);
            $this->assertEqualsData($s->ownerDocument, $frameDoc);
            $this->doc->body->appendChild($s);
            $this->assertEqualsData($s->ownerDocument, $this->doc);
        }, 'Adopting a non-orphan');
    }
}
