<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/append-on-Document.html.
class AppendOnDocumentTest extends WPTTestHarness
{
    public function helperTestAppendOnDocument()
    {
        $node = $this->doc->implementation->createDocument(null, null);
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append();
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.append() without any argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $parent->append($x);
            $this->wptAssertArrayEquals($parent->childNodes, [$x]);
        }, 'Document.append() with only one element as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($x);
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent, &$y) {
                $parent->append($y);
            });
            $this->wptAssertArrayEquals($parent->childNodes, [$x]);
        }, 'Document.append() with only one element as an argument, on a Document having one child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent) {
                $parent->append('text');
            });
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.append() with text as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent, &$x, &$y) {
                $parent->append($x, $y);
            });
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.append() with two elements as the argument, on a Document having no child.');
    }
    public function testAppendOnDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/append-on-Document.html');
        $this->helperTestAppendOnDocument();
    }
}
