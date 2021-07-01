<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/rootNode.html.
class RootNodeTest extends WPTTestHarness
{
    public function testRootNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/rootNode.html');
        $this->shadowHost = $this->doc->getElementById("shadowHost");
        $this->assertTest(function () {
            $this->shadowHost = $this->doc->createElement('div');
            $this->doc->body->appendChild($this->shadowHost);
            $shadowRoot = $this->shadowHost->attachShadow(['mode' => 'open']);
            $shadowRoot->innerHTML = '<div class="shadowChild">content</div>';
            $shadowChild = $shadowRoot->querySelector('.shadowChild');
            $this->wptAssertEquals($shadowChild->getRootNode(['composed' => true]), $this->doc, "getRootNode() must return context object's shadow-including root if options's composed is true");
            $this->wptAssertEquals($shadowChild->getRootNode(['composed' => false]), $shadowRoot, "getRootNode() must return context object's root if options's composed is false");
            $this->wptAssertEquals($shadowChild->getRootNode(), $shadowRoot, "getRootNode() must return context object's root if options's composed is default false");
        }, "getRootNode() must return context object's shadow-including root if options's composed is true, and context object's root otherwise");
        $this->assertTest(function () {
            $element = $this->doc->createElement('div');
            $this->wptAssertEquals($element->getRootNode(), $element, 'getRootNode() on an element without a parent must return the element itself');
            $text = $this->doc->createTextNode('');
            $this->wptAssertEquals($text->getRootNode(), $text, 'getRootNode() on a text node without a parent must return the text node itself');
            $processingInstruction = $this->doc->createProcessingInstruction('target', 'data');
            $this->wptAssertEquals($processingInstruction->getRootNode(), $processingInstruction, 'getRootNode() on a processing instruction node without a parent must return the processing instruction node itself');
            $this->wptAssertEquals($this->doc->getRootNode(), $this->doc, 'getRootNode() on a document node must return the document itself');
        }, 'getRootNode() must return the context object when it does not have any parent');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $element = $this->doc->createElement('div');
            $parent->appendChild($element);
            $this->wptAssertEquals($element->getRootNode(), $parent, 'getRootNode() on an element with a single ancestor must return the parent node');
            $text = $this->doc->createTextNode('');
            $parent->appendChild($text);
            $this->wptAssertEquals($text->getRootNode(), $parent, 'getRootNode() on a text node with a single ancestor must return the parent node');
            $processingInstruction = $this->doc->createProcessingInstruction('target', 'data');
            $parent->appendChild($processingInstruction);
            $this->wptAssertEquals($processingInstruction->getRootNode(), $parent, 'getRootNode() on a processing instruction node with a single ancestor must return the parent node');
        }, 'getRootNode() must return the parent node of the context object when the context object has a single ancestor not in a document');
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $this->doc->body->appendChild($parent);
            $element = $this->doc->createElement('div');
            $parent->appendChild($element);
            $this->wptAssertEquals($element->getRootNode(), $this->doc, 'getRootNode() on an element inside a document must return the document');
            $text = $this->doc->createTextNode('');
            $parent->appendChild($text);
            $this->wptAssertEquals($text->getRootNode(), $this->doc, 'getRootNode() on a text node inside a document must return the document');
            $processingInstruction = $this->doc->createProcessingInstruction('target', 'data');
            $parent->appendChild($processingInstruction);
            $this->wptAssertEquals($processingInstruction->getRootNode(), $this->doc, 'getRootNode() on a processing instruction node inside a document must return the document');
        }, 'getRootNode() must return the document when a node is in document');
        $this->assertTest(function () {
            $fragment = $this->doc->createDocumentFragment();
            $parent = $this->doc->createElement('div');
            $fragment->appendChild($parent);
            $element = $this->doc->createElement('div');
            $parent->appendChild($element);
            $this->wptAssertEquals($element->getRootNode(), $fragment, 'getRootNode() on an element inside a document fragment must return the fragment');
            $text = $this->doc->createTextNode('');
            $parent->appendChild($text);
            $this->wptAssertEquals($text->getRootNode(), $fragment, 'getRootNode() on a text node inside a document fragment must return the fragment');
            $processingInstruction = $this->doc->createProcessingInstruction('target', 'data');
            $parent->appendChild($processingInstruction);
            $this->wptAssertEquals($processingInstruction->getRootNode(), $fragment, 'getRootNode() on a processing instruction node inside a document fragment must return the fragment');
        }, 'getRootNode() must return a document fragment when a node is in the fragment');
    }
}
