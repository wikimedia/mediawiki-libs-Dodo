<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/append-on-Document.html.
class AppendOnDocumentTest extends WPTTestHarness
{
    public function testAppendOnDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/append-on-Document.html');
        $node = $this->doc->implementation->createDocument(null, null);
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append();
            $this->assertArrayEqualsData($parent->childNodes, []);
        }, 'Document.append() without any argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $parent->append($x);
            $this->assertArrayEqualsData($parent->childNodes, [$x]);
        }, 'Document.append() with only one element as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($x);
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$parent, &$y) {
                $parent->append($y);
            });
            $this->assertArrayEqualsData($parent->childNodes, [$x]);
        }, 'Document.append() with only one element as an argument, on a Document having one child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$parent) {
                $parent->append('text');
            });
            $this->assertArrayEqualsData($parent->childNodes, []);
        }, 'Document.append() with text as an argument, on a Document having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$parent, &$x, &$y) {
                $parent->append($x, $y);
            });
            $this->assertArrayEqualsData($parent->childNodes, []);
        }, 'Document.append() with two elements as the argument, on a Document having no child.');
    }
}
