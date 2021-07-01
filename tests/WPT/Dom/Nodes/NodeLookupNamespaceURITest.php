<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-lookupNamespaceURI.html.
class NodeLookupNamespaceURITest extends WPTTestHarness
{
    public function lookupNamespaceURI($node, $prefix, $expected, $name)
    {
        $this->assertTest(function () use(&$node, &$prefix, &$expected) {
            $this->wptAssertEquals($node->lookupNamespaceURI($prefix), $expected);
        }, $name);
    }
    public function isDefaultNamespace($node, $namespace, $expected, $name)
    {
        $this->assertTest(function () use(&$node, &$namespace, &$expected) {
            $this->wptAssertEquals($node->isDefaultNamespace($namespace), $expected);
        }, $name);
    }
    public function testNodeLookupNamespaceURI()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-lookupNamespaceURI.html');
        $frag = $this->doc->createDocumentFragment();
        $this->lookupNamespaceURI($frag, null, null, 'DocumentFragment should have null namespace, prefix null');
        $this->lookupNamespaceURI($frag, '', null, 'DocumentFragment should have null namespace, prefix ""');
        $this->lookupNamespaceURI($frag, 'foo', null, 'DocumentFragment should have null namespace, prefix "foo"');
        $this->lookupNamespaceURI($frag, 'xmlns', null, 'DocumentFragment should have null namespace, prefix "xmlns"');
        $this->isDefaultNamespace($frag, null, true, 'DocumentFragment is in default namespace, prefix null');
        $this->isDefaultNamespace($frag, '', true, 'DocumentFragment is in default namespace, prefix ""');
        $this->isDefaultNamespace($frag, 'foo', false, 'DocumentFragment is in default namespace, prefix "foo"');
        $this->isDefaultNamespace($frag, 'xmlns', false, 'DocumentFragment is in default namespace, prefix "xmlns"');
        $docType = $this->doc->doctype;
        $this->lookupNamespaceURI($docType, null, null, 'DocumentType should have null namespace, prefix null');
        $this->lookupNamespaceURI($docType, '', null, 'DocumentType should have null namespace, prefix ""');
        $this->lookupNamespaceURI($docType, 'foo', null, 'DocumentType should have null namespace, prefix "foo"');
        $this->lookupNamespaceURI($docType, 'xmlns', null, 'DocumentType should have null namespace, prefix "xmlns"');
        $this->isDefaultNamespace($docType, null, true, 'DocumentType is in default namespace, prefix null');
        $this->isDefaultNamespace($docType, '', true, 'DocumentType is in default namespace, prefix ""');
        $this->isDefaultNamespace($docType, 'foo', false, 'DocumentType is in default namespace, prefix "foo"');
        $this->isDefaultNamespace($docType, 'xmlns', false, 'DocumentType is in default namespace, prefix "xmlns"');
        $fooElem = $this->doc->createElementNS('fooNamespace', 'prefix:elem');
        $fooElem->setAttribute('bar', 'value');
        $this->lookupNamespaceURI($fooElem, null, null, 'Element should have null namespace, prefix null');
        $this->lookupNamespaceURI($fooElem, '', null, 'Element should have null namespace, prefix ""');
        $this->lookupNamespaceURI($fooElem, 'fooNamespace', null, 'Element should not have namespace matching prefix with namespaceURI value');
        $this->lookupNamespaceURI($fooElem, 'xmlns', null, 'Element should not have XMLNS namespace');
        $this->lookupNamespaceURI($fooElem, 'prefix', 'fooNamespace', 'Element has namespace URI matching prefix');
        $this->isDefaultNamespace($fooElem, null, true, 'Empty namespace is not default, prefix null');
        $this->isDefaultNamespace($fooElem, '', true, 'Empty namespace is not default, prefix ""');
        $this->isDefaultNamespace($fooElem, 'fooNamespace', false, 'fooNamespace is not default');
        $this->isDefaultNamespace($fooElem, 'http://www.w3.org/2000/xmlns/', false, 'xmlns namespace is not default');
        $fooElem->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:bar', 'barURI');
        $fooElem->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'bazURI');
        $this->lookupNamespaceURI($fooElem, null, 'bazURI', 'Element should have baz namespace, prefix null');
        $this->lookupNamespaceURI($fooElem, '', 'bazURI', 'Element should have baz namespace, prefix ""');
        $this->lookupNamespaceURI($fooElem, 'xmlns', null, 'Element does not has namespace with xlmns prefix');
        $this->lookupNamespaceURI($fooElem, 'bar', 'barURI', 'Element has bar namespace');
        $this->isDefaultNamespace($fooElem, null, false, 'Empty namespace is not default on fooElem, prefix null');
        $this->isDefaultNamespace($fooElem, '', false, 'Empty namespace is not default on fooElem, prefix ""');
        $this->isDefaultNamespace($fooElem, 'barURI', false, 'bar namespace is not default');
        $this->isDefaultNamespace($fooElem, 'bazURI', true, 'baz namespace is default');
        $comment = $this->doc->createComment('comment');
        $fooElem->appendChild($comment);
        $this->lookupNamespaceURI($comment, null, 'bazURI', 'Comment should inherit baz namespace');
        $this->lookupNamespaceURI($comment, '', 'bazURI', 'Comment should inherit  baz namespace');
        $this->lookupNamespaceURI($comment, 'prefix', 'fooNamespace', 'Comment should inherit namespace URI matching prefix');
        $this->lookupNamespaceURI($comment, 'bar', 'barURI', 'Comment should inherit bar namespace');
        $this->isDefaultNamespace($comment, null, false, 'For comment, empty namespace is not default, prefix null');
        $this->isDefaultNamespace($comment, '', false, 'For comment, empty namespace is not default, prefix ""');
        $this->isDefaultNamespace($comment, 'fooNamespace', false, 'For comment, fooNamespace is not default');
        $this->isDefaultNamespace($comment, 'http://www.w3.org/2000/xmlns/', false, 'For comment, xmlns namespace is not default');
        $this->isDefaultNamespace($comment, 'barURI', false, 'For comment, inherited bar namespace is not default');
        $this->isDefaultNamespace($comment, 'bazURI', true, 'For comment, inherited baz namespace is default');
        $fooChild = $this->doc->createElementNS('childNamespace', 'childElem');
        $fooElem->appendChild($fooChild);
        $this->lookupNamespaceURI($fooChild, null, 'childNamespace', 'Child element should inherit baz namespace');
        $this->lookupNamespaceURI($fooChild, '', 'childNamespace', 'Child element should have null namespace');
        $this->lookupNamespaceURI($fooChild, 'xmlns', null, 'Child element should not have XMLNS namespace');
        $this->lookupNamespaceURI($fooChild, 'prefix', 'fooNamespace', 'Child element has namespace URI matching prefix');
        $this->isDefaultNamespace($fooChild, null, false, 'Empty namespace is not default for child, prefix null');
        $this->isDefaultNamespace($fooChild, '', false, 'Empty namespace is not default for child, prefix ""');
        $this->isDefaultNamespace($fooChild, 'fooNamespace', false, 'fooNamespace is not default for child');
        $this->isDefaultNamespace($fooChild, 'http://www.w3.org/2000/xmlns/', false, 'xmlns namespace is not default for child');
        $this->isDefaultNamespace($fooChild, 'barURI', false, 'bar namespace is not default for child');
        $this->isDefaultNamespace($fooChild, 'bazURI', false, 'baz namespace is default for child');
        $this->isDefaultNamespace($fooChild, 'childNamespace', true, 'childNamespace is default for child');
        $this->doc->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:bar', 'barURI');
        $this->doc->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'bazURI');
        $this->lookupNamespaceURI($this->doc, null, 'http://www.w3.org/1999/xhtml', 'Document should have xhtml namespace, prefix null');
        $this->lookupNamespaceURI($this->doc, '', 'http://www.w3.org/1999/xhtml', 'Document should have xhtml namespace, prefix ""');
        $this->lookupNamespaceURI($this->doc, 'prefix', null, 'Document has no namespace URI matching prefix');
        $this->lookupNamespaceURI($this->doc, 'bar', 'barURI', 'Document has bar namespace');
        $this->isDefaultNamespace($this->doc, null, false, 'For document, empty namespace is not default, prefix null');
        $this->isDefaultNamespace($this->doc, '', false, 'For document, empty namespace is not default, prefix ""');
        $this->isDefaultNamespace($this->doc, 'fooNamespace', false, 'For document, fooNamespace is not default');
        $this->isDefaultNamespace($this->doc, 'http://www.w3.org/2000/xmlns/', false, 'For document, xmlns namespace is not default');
        $this->isDefaultNamespace($this->doc, 'barURI', false, 'For document, bar namespace is not default');
        $this->isDefaultNamespace($this->doc, 'bazURI', false, 'For document, baz namespace is not default');
        $this->isDefaultNamespace($this->doc, 'http://www.w3.org/1999/xhtml', true, 'For document, xhtml namespace is default');
        $comment = $this->doc->createComment('comment');
        $this->doc->appendChild($comment);
        $this->lookupNamespaceURI($comment, 'bar', null, 'Comment does not have bar namespace');
    }
}
