<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/prepend-on-Document.html.
class PrependOnDocumentTest extends WPTTestHarness
{
    public function helperTestPrependOnDocument()
    {
        $node = $this->doc->implementation->createDocument(null, null);
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->prepend();
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.prepend() without any argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $parent->prepend($x);
            $this->wptAssertArrayEquals($parent->childNodes, [$x]);
        }, 'Document.prepend() with only one element as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($x);
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent, &$y) {
                $parent->prepend($y);
            });
            $this->wptAssertArrayEquals($parent->childNodes, [$x]);
        }, 'Document.append() with only one element as an argument, on a Document having one child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent) {
                $parent->prepend('text');
            });
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.prepend() with text as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$parent, &$x, &$y) {
                $parent->prepend($x, $y);
            });
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, 'Document.prepend() with two elements as the argument, on a Document having no child.');
    }
    public function testPrependOnDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/prepend-on-Document.html');
        $this->helperTestPrependOnDocument();
    }
}
