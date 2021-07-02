<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-parentElement.html.
class NodeParentElementTest extends WPTTestHarness
{
    public function testNodeParentElement()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-parentElement.html');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->parentElement, null);
        }, 'When the parent is null, parentElement should be null');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->doctype->parentElement, null);
        }, 'When the parent is a document, parentElement should be null (doctype)');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->documentElement->parentElement, null);
        }, 'When the parent is a document, parentElement should be null (element)');
        $this->assertTest(function () {
            $comment = $this->doc->appendChild($this->doc->createComment('foo'));
            $this->wptAssertEquals($comment->parentElement, null);
        }, 'When the parent is a document, parentElement should be null (comment)');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $this->wptAssertEquals($df->parentElement, null);
            $el = $this->doc->createElement('div');
            $this->wptAssertEquals($el->parentElement, null);
            $df->appendChild($el);
            $this->wptAssertEquals($el->parentNode, $df);
            $this->wptAssertEquals($el->parentElement, null);
        }, 'parentElement should return null for children of DocumentFragments (element)');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $this->wptAssertEquals($df->parentElement, null);
            $text = $this->doc->createTextNode('bar');
            $this->wptAssertEquals($text->parentElement, null);
            $df->appendChild($text);
            $this->wptAssertEquals($text->parentNode, $df);
            $this->wptAssertEquals($text->parentElement, null);
        }, 'parentElement should return null for children of DocumentFragments (text)');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $parent = $this->doc->createElement('div');
            $df->appendChild($parent);
            $el = $this->doc->createElement('div');
            $this->wptAssertEquals($el->parentElement, null);
            $parent->appendChild($el);
            $this->wptAssertEquals($el->parentElement, $parent);
        }, 'parentElement should work correctly with DocumentFragments (element)');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $parent = $this->doc->createElement('div');
            $df->appendChild($parent);
            $text = $this->doc->createTextNode('bar');
            $this->wptAssertEquals($text->parentElement, null);
            $parent->appendChild($text);
            $this->wptAssertEquals($text->parentElement, $parent);
        }, 'parentElement should work correctly with DocumentFragments (text)');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $el = $this->doc->createElement('div');
            $this->wptAssertEquals($el->parentElement, null);
            $parent->appendChild($el);
            $this->wptAssertEquals($el->parentElement, $parent);
        }, 'parentElement should work correctly in disconnected subtrees (element)');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $text = $this->doc->createTextNode('bar');
            $this->wptAssertEquals($text->parentElement, null);
            $parent->appendChild($text);
            $this->wptAssertEquals($text->parentElement, $parent);
        }, 'parentElement should work correctly in disconnected subtrees (text)');
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $this->wptAssertEquals($el->parentElement, null);
            $this->doc->body->appendChild($el);
            $this->wptAssertEquals($el->parentElement, $this->doc->body);
        }, 'parentElement should work correctly in a document (element)');
        $this->assertTest(function () {
            $text = $this->doc->createElement('div');
            $this->wptAssertEquals($text->parentElement, null);
            $this->doc->body->appendChild($text);
            $this->wptAssertEquals($text->parentElement, $this->doc->body);
        }, 'parentElement should work correctly in a document (text)');
    }
}
