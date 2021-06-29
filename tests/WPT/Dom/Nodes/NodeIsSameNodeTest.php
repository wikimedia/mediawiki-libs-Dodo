<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-isSameNode.html.
class NodeIsSameNodeTest extends WPTTestHarness
{
    public function testNodeIsSameNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-isSameNode.html');
        $this->assertTest(function () {
            $doctype1 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId', 'systemId');
            $doctype2 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId', 'systemId');
            $this->assertTrueData($doctype1->isSameNode($doctype1), 'self-comparison');
            $this->assertFalseData($doctype1->isSameNode($doctype2), 'same properties');
            $this->assertFalseData($doctype1->isSameNode(null), 'with null other node');
        }, 'doctypes should be compared on reference');
        $this->assertTest(function () {
            $element1 = $this->doc->createElementNS('namespace', 'prefix:localName');
            $element2 = $this->doc->createElementNS('namespace', 'prefix:localName');
            $this->assertTrueData($element1->isSameNode($element1), 'self-comparison');
            $this->assertFalseData($element1->isSameNode($element2), 'same properties');
            $this->assertFalseData($element1->isSameNode(null), 'with null other node');
        }, 'elements should be compared on reference (namespaced element)');
        $this->assertTest(function () {
            $element1 = $this->doc->createElement('element');
            $element1->setAttributeNS('namespace', 'prefix:localName', 'value');
            $element2 = $this->doc->createElement('element');
            $element2->setAttributeNS('namespace', 'prefix:localName', 'value');
            $this->assertTrueData($element1->isSameNode($element1), 'self-comparison');
            $this->assertFalseData($element1->isSameNode($element2), 'same properties');
            $this->assertFalseData($element1->isSameNode(null), 'with null other node');
        }, 'elements should be compared on reference (namespaced attribute)');
        $this->assertTest(function () {
            $pi1 = $this->doc->createProcessingInstruction('target', 'data');
            $pi2 = $this->doc->createProcessingInstruction('target', 'data');
            $this->assertTrueData($pi1->isSameNode($pi1), 'self-comparison');
            $this->assertFalseData($pi1->isSameNode($pi2), 'different target');
            $this->assertFalseData($pi1->isSameNode(null), 'with null other node');
        }, 'processing instructions should be compared on reference');
        $this->assertTest(function () {
            $text1 = $this->doc->createTextNode('data');
            $text2 = $this->doc->createTextNode('data');
            $this->assertTrueData($text1->isSameNode($text1), 'self-comparison');
            $this->assertFalseData($text1->isSameNode($text2), 'same properties');
            $this->assertFalseData($text1->isSameNode(null), 'with null other node');
        }, 'text nodes should be compared on reference');
        $this->assertTest(function () {
            $comment1 = $this->doc->createComment('data');
            $comment2 = $this->doc->createComment('data');
            $this->assertTrueData($comment1->isSameNode($comment1), 'self-comparison');
            $this->assertFalseData($comment1->isSameNode($comment2), 'same properties');
            $this->assertFalseData($comment1->isSameNode(null), 'with null other node');
        }, 'comments should be compared on reference');
        $this->assertTest(function () {
            $this->docFragment1 = $this->doc->createDocumentFragment();
            $this->docFragment2 = $this->doc->createDocumentFragment();
            $this->assertTrueData($this->docFragment1->isSameNode($this->docFragment1), 'self-comparison');
            $this->assertFalseData($this->docFragment1->isSameNode($this->docFragment2), 'same properties');
            $this->assertFalseData($this->docFragment1->isSameNode(null), 'with null other node');
        }, 'document fragments should be compared on reference');
        $this->assertTest(function () {
            $this->doc1 = $this->doc->implementation->createDocument('', '');
            $this->doc2 = $this->doc->implementation->createDocument('', '');
            $this->assertTrueData($this->doc1->isSameNode($this->doc1), 'self-comparison');
            $this->assertFalseData($this->doc1->isSameNode($this->doc2), 'another empty XML document');
            $this->assertFalseData($this->doc1->isSameNode(null), 'with null other node');
        }, 'documents should be compared on reference');
        $this->assertTest(function () {
            $attr1 = $this->doc->createAttribute('href');
            $attr2 = $this->doc->createAttribute('href');
            $this->assertTrueData($attr1->isSameNode($attr1), 'self-comparison');
            $this->assertFalseData($attr1->isSameNode($attr2), 'same name');
            $this->assertFalseData($attr1->isSameNode(null), 'with null other node');
        }, 'attributes should be compared on reference');
    }
}
