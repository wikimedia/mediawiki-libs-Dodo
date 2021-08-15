<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-appendChild.html.
class NodeAppendChildTest extends WPTTestHarness
{
    public function helperTestLeaf($node, $desc)
    {
        // WebIDL.
        $this->assertTest(function () use(&$node) {
            $this->wptAssertThrowsJs($this->type_error, function () use(&$node) {
                $node->appendChild(null);
            });
        }, 'Appending null to a ' . $desc);
        // Pre-insert step 1.
        $this->assertTest(function () use(&$node) {
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () use(&$node) {
                $node->appendChild($this->doc->createTextNode('fail'));
            });
        }, 'Appending to a ' . $desc);
    }
    public function testNodeAppendChild()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-appendChild.html');
        // WebIDL.
        $this->assertTest(function () {
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->appendChild(null);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->appendChild(['a' => 'b']);
            });
        }, 'WebIDL tests');
        // WebIDL and pre-insert step 1.
        $this->assertTest(function () {
            $this->helperTestLeaf($this->doc->createTextNode('Foo'), 'text node');
            $this->helperTestLeaf($this->doc->createComment('Foo'), 'comment');
            $this->helperTestLeaf($this->doc->doctype, 'doctype');
        }, 'Appending to a leaf node.');
        // Pre-insert step 5.
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () use(&$frameDoc) {
                $this->doc->body->appendChild($frameDoc);
            });
        }, 'Appending a document');
        // Pre-insert step 8.
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $s = $frameDoc->createElement('a');
            $this->wptAssertEquals($s->ownerDocument, $frameDoc);
            $this->doc->body->appendChild($s);
            $this->wptAssertEquals($s->ownerDocument, $this->doc);
        }, 'Adopting an orphan');
        $this->assertTest(function () {
            $frameDoc = $frames[0]->document;
            $s = $frameDoc->createElement('b');
            $this->wptAssertEquals($s->ownerDocument, $frameDoc);
            $frameDoc->body->appendChild($s);
            $this->wptAssertEquals($s->ownerDocument, $frameDoc);
            $this->doc->body->appendChild($s);
            $this->wptAssertEquals($s->ownerDocument, $this->doc);
        }, 'Adopting a non-orphan');
    }
}
