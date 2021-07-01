<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-append.html.
class ParentNodeAppendTest extends WPTTestHarness
{
    public function testAppend($node, $nodeName)
    {
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append();
            $this->wptAssertArrayEquals($parent->childNodes, []);
        }, $nodeName . '.append() without any argument, on a parent having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append(null);
            $this->wptAssertEquals($parent->childNodes[0]->textContent, 'null');
        }, $nodeName . '.append() with null as an argument, on a parent having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append(null);
            $this->wptAssertEquals($parent->childNodes[0]->textContent, NULL);
        }, $nodeName . '.append() with undefined as an argument, on a parent having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $parent->append('text');
            $this->wptAssertEquals($parent->childNodes[0]->textContent, 'text');
        }, $nodeName . '.append() with only text as an argument, on a parent having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $parent->append($x);
            $this->wptAssertArrayEquals($parent->childNodes, [$x]);
        }, $nodeName . '.append() with only one element as an argument, on a parent having no child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $child = $this->doc->createElement('test');
            $parent->appendChild($child);
            $parent->append(null);
            $this->wptAssertEquals($parent->childNodes[0], $child);
            $this->wptAssertEquals($parent->childNodes[1]->textContent, 'null');
        }, $nodeName . '.append() with null as an argument, on a parent having a child.');
        $this->assertTest(function () use(&$node) {
            $parent = $node->cloneNode();
            $x = $this->doc->createElement('x');
            $child = $this->doc->createElement('test');
            $parent->appendChild($child);
            $parent->append($x, 'text');
            $this->wptAssertEquals($parent->childNodes[0], $child);
            $this->wptAssertEquals($parent->childNodes[1], $x);
            $this->wptAssertEquals($parent->childNodes[2]->textContent, 'text');
        }, $nodeName . '.append() with one element and text as argument, on a parent having a child.');
    }
    public function preInsertionValidateHierarchy($methodName)
    {
        // Step 2
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc) {
                return $this->insert($doc->body, $doc->body);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc) {
                return $this->insert($doc->body, $doc->documentElement);
            });
        }, 'If node is a host-including inclusive ancestor of parent, then throw a HierarchyRequestError DOMException.');
        // Step 4
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doc2 = $this->doc->implementation->createHTMLDocument('title2');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doc2) {
                return $this->insert($doc, $doc2);
            });
        }, 'If node is not a DocumentFragment, DocumentType, Element, Text, ProcessingInstruction, or Comment node, then throw a HierarchyRequestError DOMException.');
        // Step 5, in case of inserting a text node into a document
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc) {
                return $this->insert($doc, $doc->createTextNode('text'));
            });
        }, 'If node is a Text node and parent is a document, then throw a HierarchyRequestError DOMException.');
        // Step 5, in case of inserting a doctype into a non-document
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doctype = $doc->childNodes[0];
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                return $this->insert($doc->createElement('a'), $doctype);
            });
        }, 'If node is a doctype and parent is not a document, then throw a HierarchyRequestError DOMException.');
        // Step 6, in case of DocumentFragment including multiple elements
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doc->documentElement->remove();
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $df->appendChild($doc->createElement('b'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                return $this->insert($doc, $df);
            });
        }, 'If node is a DocumentFragment with multiple elements and parent is a document, then throw a HierarchyRequestError DOMException.');
        // Step 6, in case of DocumentFragment has multiple elements when document already has an element
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                return $this->insert($doc, $df);
            });
        }, 'If node is a DocumentFragment with an element and parent is a document with another element, then throw a HierarchyRequestError DOMException.');
        // Step 6, in case of an element
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $el = $doc->createElement('a');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$el) {
                return $this->insert($doc, $el);
            });
        }, 'If node is an Element and parent is a document with another element, then throw a HierarchyRequestError DOMException.');
        // Step 6, in case of a doctype when document already has another doctype
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doctype = $doc->childNodes[0]->cloneNode();
            $doc->documentElement->remove();
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                return $this->insert($doc, $doctype);
            });
        }, 'If node is a doctype and parent is a document with another doctype, then throw a HierarchyRequestError DOMException.');
        // Step 6, in case of a doctype when document has an element
        if ($methodName !== 'prepend') {
            // Skip `.prepend` as this doesn't throw if `child` is an element
            $this->assertTest(function () {
                $doc = $this->doc->implementation->createHTMLDocument('title');
                $doctype = $doc->childNodes[0]->cloneNode();
                $doc->childNodes[0]->remove();
                $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                    return $this->insert($doc, $doctype);
                });
            }, 'If node is a doctype and parent is a document with an element, then throw a HierarchyRequestError DOMException.');
        }
    }
    public function insert($parent, $node, &$methodName)
    {
        if (count($parent[$methodName]) > 1) {
            // This is for insertBefore(). We can't blindly pass `null` for all methods
            // as doing so will move nodes before validation.
            $parent[$methodName]($node, null);
        } else {
            $parent[$methodName]($node);
        }
    }
    public function testParentNodeAppend()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-append.html');
        $this->preInsertionValidateHierarchy('append');
        $this->testAppend($this->doc->createElement('div'), 'Element');
        $this->testAppend($this->doc->createDocumentFragment(), 'DocumentFragment');
    }
}
