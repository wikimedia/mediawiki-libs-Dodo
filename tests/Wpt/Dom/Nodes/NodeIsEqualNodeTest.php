<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-isEqualNode.html.
class NodeIsEqualNodeTest extends WptTestHarness
{
    public function testDeepEquality($parentFactory)
    {
        // Some ad-hoc tests of deep equality
        $parentA = $parentFactory();
        $parentB = $parentFactory();
        $parentA->appendChild($this->doc->createComment('data'));
        $this->assertFalseData($parentA->isEqualNode($parentB));
        $parentB->appendChild($this->doc->createComment('data'));
        $this->assertTrueData($parentA->isEqualNode($parentB));
    }
    public function testNodeIsEqualNode()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-isEqualNode.html');
        $this->assertTest(function () {
            $doctype1 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId', 'systemId');
            $doctype2 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId', 'systemId');
            $doctype3 = $this->doc->implementation->createDocumentType('qualifiedName2', 'publicId', 'systemId');
            $doctype4 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId2', 'systemId');
            $doctype5 = $this->doc->implementation->createDocumentType('qualifiedName', 'publicId', 'systemId3');
            $this->assertTrueData($doctype1->isEqualNode($doctype1), 'self-comparison');
            $this->assertTrueData($doctype1->isEqualNode($doctype2), 'same properties');
            $this->assertFalseData($doctype1->isEqualNode($doctype3), 'different name');
            $this->assertFalseData($doctype1->isEqualNode($doctype4), 'different public ID');
            $this->assertFalseData($doctype1->isEqualNode($doctype5), 'different system ID');
        }, 'doctypes should be compared on name, public ID, and system ID');
        $this->assertTest(function () {
            $element1 = $this->doc->createElementNS('namespace', 'prefix:localName');
            $element2 = $this->doc->createElementNS('namespace', 'prefix:localName');
            $element3 = $this->doc->createElementNS('namespace2', 'prefix:localName');
            $element4 = $this->doc->createElementNS('namespace', 'prefix2:localName');
            $element5 = $this->doc->createElementNS('namespace', 'prefix:localName2');
            $element6 = $this->doc->createElementNS('namespace', 'prefix:localName');
            $element6->setAttribute('foo', 'bar');
            $this->assertTrueData($element1->isEqualNode($element1), 'self-comparison');
            $this->assertTrueData($element1->isEqualNode($element2), 'same properties');
            $this->assertFalseData($element1->isEqualNode($element3), 'different namespace');
            $this->assertFalseData($element1->isEqualNode($element4), 'different prefix');
            $this->assertFalseData($element1->isEqualNode($element5), 'different local name');
            $this->assertFalseData($element1->isEqualNode($element6), 'different number of attributes');
        }, 'elements should be compared on namespace, namespace prefix, local name, and number of attributes');
        $this->assertTest(function () {
            $element1 = $this->doc->createElement('element');
            $element1->setAttributeNS('namespace', 'prefix:localName', 'value');
            $element2 = $this->doc->createElement('element');
            $element2->setAttributeNS('namespace', 'prefix:localName', 'value');
            $element3 = $this->doc->createElement('element');
            $element3->setAttributeNS('namespace2', 'prefix:localName', 'value');
            $element4 = $this->doc->createElement('element');
            $element4->setAttributeNS('namespace', 'prefix2:localName', 'value');
            $element5 = $this->doc->createElement('element');
            $element5->setAttributeNS('namespace', 'prefix:localName2', 'value');
            $element6 = $this->doc->createElement('element');
            $element6->setAttributeNS('namespace', 'prefix:localName', 'value2');
            $this->assertTrueData($element1->isEqualNode($element1), 'self-comparison');
            $this->assertTrueData($element1->isEqualNode($element2), 'attribute with same properties');
            $this->assertFalseData($element1->isEqualNode($element3), 'attribute with different namespace');
            $this->assertTrueData($element1->isEqualNode($element4), 'attribute with different prefix');
            $this->assertFalseData($element1->isEqualNode($element5), 'attribute with different local name');
            $this->assertFalseData($element1->isEqualNode($element6), 'attribute with different value');
        }, 'elements should be compared on attribute namespace, local name, and value');
        $this->assertTest(function () {
            $pi1 = $this->doc->createProcessingInstruction('target', 'data');
            $pi2 = $this->doc->createProcessingInstruction('target', 'data');
            $pi3 = $this->doc->createProcessingInstruction('target2', 'data');
            $pi4 = $this->doc->createProcessingInstruction('target', 'data2');
            $this->assertTrueData($pi1->isEqualNode($pi1), 'self-comparison');
            $this->assertTrueData($pi1->isEqualNode($pi2), 'same properties');
            $this->assertFalseData($pi1->isEqualNode($pi3), 'different target');
            $this->assertFalseData($pi1->isEqualNode($pi4), 'different data');
        }, 'processing instructions should be compared on target and data');
        $this->assertTest(function () {
            $text1 = $this->doc->createTextNode('data');
            $text2 = $this->doc->createTextNode('data');
            $text3 = $this->doc->createTextNode('data2');
            $this->assertTrueData($text1->isEqualNode($text1), 'self-comparison');
            $this->assertTrueData($text1->isEqualNode($text2), 'same properties');
            $this->assertFalseData($text1->isEqualNode($text3), 'different data');
        }, 'text nodes should be compared on data');
        $this->assertTest(function () {
            $comment1 = $this->doc->createComment('data');
            $comment2 = $this->doc->createComment('data');
            $comment3 = $this->doc->createComment('data2');
            $this->assertTrueData($comment1->isEqualNode($comment1), 'self-comparison');
            $this->assertTrueData($comment1->isEqualNode($comment2), 'same properties');
            $this->assertFalseData($comment1->isEqualNode($comment3), 'different data');
        }, 'comments should be compared on data');
        $this->assertTest(function () {
            $this->docFragment1 = $this->doc->createDocumentFragment();
            $this->docFragment2 = $this->doc->createDocumentFragment();
            $this->assertTrueData($this->docFragment1->isEqualNode($this->docFragment1), 'self-comparison');
            $this->assertTrueData($this->docFragment1->isEqualNode($this->docFragment2), 'same properties');
        }, 'document fragments should not be compared based on properties');
        $this->assertTest(function () {
            $this->doc1 = $this->doc->implementation->createDocument('', '');
            $this->doc2 = $this->doc->implementation->createDocument('', '');
            $this->assertTrueData($this->doc1->isEqualNode($this->doc1), 'self-comparison');
            $this->assertTrueData($this->doc1->isEqualNode($this->doc2), 'another empty XML document');
            $htmlDoctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->doc3 = $this->doc->implementation->createDocument('http://www.w3.org/1999/xhtml', 'html', $htmlDoctype);
            $this->doc3->documentElement->appendChild($this->doc3->createElement('head'));
            $this->doc3->documentElement->appendChild($this->doc3->createElement('body'));
            $this->doc4 = $this->doc->implementation->createHTMLDocument();
            $this->assertTrueData($this->doc3->isEqualNode($this->doc4), 'default HTML documents, created different ways');
        }, 'documents should not be compared based on properties');
        $this->assertTest(function () {
            $this->testDeepEquality(function () {
                return $this->doc->createElement('foo');
            });
            $this->testDeepEquality(function () {
                return $this->doc->createDocumentFragment();
            });
            $this->testDeepEquality(function () {
                return $this->doc->implementation->createDocument('', '');
            });
            $this->testDeepEquality(function () {
                return $this->doc->implementation->createHTMLDocument();
            });
        }, 'node equality testing should test descendant equality too');
    }
}
