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
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-insertBefore.html.
class NodeInsertBeforeTest extends WPTTestHarness
{
    public function testLeafNode($nodeName, $createNodeFunction)
    {
        $this->assertTest(function () use(&$createNodeFunction) {
            $node = $createNodeFunction();
            $this->wptAssertThrowsJs($this->type_error, function () use(&$node) {
                $node->insertBefore(null, null);
            });
        }, 'Calling insertBefore with a non-Node first argument on a leaf node ' . $nodeName . ' must throw TypeError.');
        $this->assertTest(function () use(&$createNodeFunction) {
            $node = $createNodeFunction();
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () use(&$node) {
                $node->insertBefore($this->doc->createTextNode('fail'), null);
            });
            // Would be step 2.
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () use(&$node) {
                $node->insertBefore($node, null);
            });
            // Would be step 3.
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () use(&$node) {
                $node->insertBefore($node, $this->doc->createTextNode('child'));
            });
        }, 'Calling insertBefore an a leaf node ' . $nodeName . ' must throw HIERARCHY_REQUEST_ERR.');
    }
    public function getNonParentNodes()
    {
        return [$this->doc->implementation->createDocumentType('html', '', ''), $this->doc->createTextNode('text'), $this->doc->implementation->createDocument(null, 'foo', null)->createProcessingInstruction('foo', 'bar'), $this->doc->createComment('comment'), $this->doc->implementation->createDocument(null, 'foo', null)->createCDATASection('data')];
    }
    public function getNonInsertableNodes()
    {
        return [$this->doc->implementation->createHTMLDocument('title')];
    }
    public function getNonDocumentParentNodes()
    {
        return [$this->doc->createElement('div'), $this->doc->createDocumentFragment()];
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
    public function testNodeInsertBefore()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-insertBefore.html');
        $insertFunc = 'insertBefore';
        $this->preInsertionValidateHierarchy('insertBefore');
        $this->assertTest(function () {
            // WebIDL.
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->insertBefore(null, null);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->insertBefore(null, $this->doc->body->firstChild);
            });
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->body->insertBefore(['a' => 'b'], $this->doc->body->firstChild);
            });
        }, 'Calling insertBefore with a non-Node first argument must throw TypeError.');
        $this->testLeafNode('DocumentType', function () {
            return $this->doc->doctype;
        });
        $this->testLeafNode('Text', function () {
            return $this->doc->createTextNode('Foo');
        });
        $this->testLeafNode('Comment', function () {
            return $this->doc->createComment('Foo');
        });
        $this->testLeafNode('ProcessingInstruction', function () {
            return $this->doc->createProcessingInstruction('foo', 'bar');
        });
        $this->assertTest(function () {
            // Step 2.
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () {
                $this->doc->body->insertBefore($this->doc->body, $this->doc->getElementById('log'));
            });
            $this->wptAssertThrowsDom('HIERARCHY_REQUEST_ERR', function () {
                $this->doc->body->insertBefore($this->doc->documentElement, $this->doc->getElementById('log'));
            });
        }, 'Calling insertBefore with an inclusive ancestor of the context object must throw HIERARCHY_REQUEST_ERR.');
        // Step 3.
        $this->assertTest(function () {
            $a = $this->doc->createElement('div');
            $b = $this->doc->createElement('div');
            $c = $this->doc->createElement('div');
            $this->wptAssertThrowsDom('NotFoundError', function () use(&$a, &$b, &$c) {
                $a->insertBefore($b, $c);
            });
        }, 'Calling insertBefore with a reference child whose parent is not the context node must throw a NotFoundError.');
        // Step 4.1.
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doc2 = $this->doc->implementation->createHTMLDocument('title2');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doc2) {
                $doc->insertBefore($doc2, $doc->documentElement);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc) {
                $doc->insertBefore($doc->createTextNode('text'), $doc->documentElement);
            });
        }, 'If the context node is a document, inserting a document or text node should throw a HierarchyRequestError.');
        // Step 4.2.1.
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $doc->removeChild($doc->documentElement);
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $df->appendChild($doc->createElement('b'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->firstChild);
            });
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createTextNode('text'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->firstChild);
            });
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createComment('comment'));
            $df->appendChild($doc->createTextNode('text'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->firstChild);
            });
        }, 'If the context node is a document, inserting a DocumentFragment that contains a text node or too many elements should throw a HierarchyRequestError.');
        // Step 4.2.2.
        $this->assertTest(function () {
            // The context node has an element child.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->appendChild($doc->createComment('foo'));
            $this->wptAssertArrayEquals($doc->childNodes, [$doc->doctype, $doc->documentElement, $comment]);
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->doctype);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->documentElement);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df, &$comment) {
                $doc->insertBefore($df, $comment);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, null);
            });
        }, 'If the context node is a document, inserting a DocumentFragment with an element if there already is an element child should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            // /child/ is a doctype.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->insertBefore($doc->createComment('foo'), $doc->firstChild);
            $doc->removeChild($doc->documentElement);
            $this->wptAssertArrayEquals($doc->childNodes, [$comment, $doc->doctype]);
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df) {
                $doc->insertBefore($df, $doc->doctype);
            });
        }, 'If the context node is a document and a doctype is following the reference child, inserting a DocumentFragment with an element should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            // /child/ is not null and a doctype is following /child/.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->insertBefore($doc->createComment('foo'), $doc->firstChild);
            $doc->removeChild($doc->documentElement);
            $this->wptAssertArrayEquals($doc->childNodes, [$comment, $doc->doctype]);
            $df = $doc->createDocumentFragment();
            $df->appendChild($doc->createElement('a'));
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$df, &$comment) {
                $doc->insertBefore($df, $comment);
            });
        }, 'If the context node is a document, inserting a DocumentFragment with an element before the doctype should throw a HierarchyRequestError.');
        // Step 4.3.
        $this->assertTest(function () {
            // The context node has an element child.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->appendChild($doc->createComment('foo'));
            $this->wptAssertArrayEquals($doc->childNodes, [$doc->doctype, $doc->documentElement, $comment]);
            $a = $doc->createElement('a');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a) {
                $doc->insertBefore($a, $doc->doctype);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a) {
                $doc->insertBefore($a, $doc->documentElement);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a, &$comment) {
                $doc->insertBefore($a, $comment);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a) {
                $doc->insertBefore($a, null);
            });
        }, 'If the context node is a document, inserting an element if there already is an element child should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            // /child/ is a doctype.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->insertBefore($doc->createComment('foo'), $doc->firstChild);
            $doc->removeChild($doc->documentElement);
            $this->wptAssertArrayEquals($doc->childNodes, [$comment, $doc->doctype]);
            $a = $doc->createElement('a');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a) {
                $doc->insertBefore($a, $doc->doctype);
            });
        }, 'If the context node is a document, inserting an element before the doctype should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            // /child/ is not null and a doctype is following /child/.
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->insertBefore($doc->createComment('foo'), $doc->firstChild);
            $doc->removeChild($doc->documentElement);
            $this->wptAssertArrayEquals($doc->childNodes, [$comment, $doc->doctype]);
            $a = $doc->createElement('a');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$a, &$comment) {
                $doc->insertBefore($a, $comment);
            });
        }, 'If the context node is a document and a doctype is following the reference child, inserting an element should throw a HierarchyRequestError.');
        // Step 4.4.
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->insertBefore($doc->createComment('foo'), $doc->firstChild);
            $this->wptAssertArrayEquals($doc->childNodes, [$comment, $doc->doctype, $doc->documentElement]);
            $doctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype, &$comment) {
                $doc->insertBefore($doctype, $comment);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                $doc->insertBefore($doctype, $doc->doctype);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                $doc->insertBefore($doctype, $doc->documentElement);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                $doc->insertBefore($doctype, null);
            });
        }, 'If the context node is a document, inserting a doctype if there already is a doctype child should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->appendChild($doc->createComment('foo'));
            $doc->removeChild($doc->doctype);
            $this->wptAssertArrayEquals($doc->childNodes, [$doc->documentElement, $comment]);
            $doctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype, &$comment) {
                $doc->insertBefore($doctype, $comment);
            });
        }, 'If the context node is a document, inserting a doctype after the document element should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $comment = $doc->appendChild($doc->createComment('foo'));
            $doc->removeChild($doc->doctype);
            $this->wptAssertArrayEquals($doc->childNodes, [$doc->documentElement, $comment]);
            $doctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$doc, &$doctype) {
                $doc->insertBefore($doctype, null);
            });
        }, 'If the context node is a document with and element child, appending a doctype should throw a HierarchyRequestError.');
        // Step 5.
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $a = $df->appendChild($this->doc->createElement('a'));
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$df, &$doc, &$a) {
                $df->insertBefore($doc, $a);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$df, &$doc) {
                $df->insertBefore($doc, null);
            });
            $doctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$df, &$doctype, &$a) {
                $df->insertBefore($doctype, $a);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$df, &$doctype) {
                $df->insertBefore($doctype, null);
            });
        }, 'If the context node is a DocumentFragment, inserting a document or a doctype should throw a HierarchyRequestError.');
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $a = $el->appendChild($this->doc->createElement('a'));
            $doc = $this->doc->implementation->createHTMLDocument('title');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$el, &$doc, &$a) {
                $el->insertBefore($doc, $a);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$el, &$doc) {
                $el->insertBefore($doc, null);
            });
            $doctype = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$el, &$doctype, &$a) {
                $el->insertBefore($doctype, $a);
            });
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use(&$el, &$doctype) {
                $el->insertBefore($doctype, null);
            });
        }, 'If the context node is an element, inserting a document or a doctype should throw a HierarchyRequestError.');
        // Step 7.
        $this->assertTest(function () {
            $a = $this->doc->createElement('div');
            $b = $this->doc->createElement('div');
            $c = $this->doc->createElement('div');
            $a->appendChild($b);
            $a->appendChild($c);
            $this->wptAssertArrayEquals($a->childNodes, [$b, $c]);
            $this->wptAssertEquals($a->insertBefore($b, $b), $b);
            $this->wptAssertArrayEquals($a->childNodes, [$b, $c]);
            $this->wptAssertEquals($a->insertBefore($c, $c), $c);
            $this->wptAssertArrayEquals($a->childNodes, [$b, $c]);
        }, 'Inserting a node before itself should not move the node');
        // Test that the steps happen in the right order, to the extent that it's
        // observable.   The variable names "parent", "child", and "node" match the
        // corresponding variables in the replaceChild algorithm in these tests.
        // Step 1 happens before step 3.
        $this->assertTest(function () {
            $illegalParents = $this->getNonParentNodes();
            $child = $this->doc->createElement('div');
            $node = $this->doc->createElement('div');
            foreach ($illegalParents as $parent) {
                $this->wptAssertThrowsDom('HierarchyRequestError', function () {
                    call_user_func('insertFunc', $node, $child);
                });
            }
        }, "Should check the 'parent' type before checking whether 'child' is a child of 'parent'");
        // Step 2 happens before step 3.
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $child = $this->doc->createElement('div');
            $node = $this->doc->createElement('div');
            $node->appendChild($parent);
            $this->wptAssertThrowsDom('HierarchyRequestError', function () {
                call_user_func('insertFunc', $node, $child);
            });
        }, "Should check that 'node' is not an ancestor of 'parent' before checking whether 'child' is a child of 'parent'");
        // Step 3 happens before step 4.
        $this->assertTest(function () {
            $parent = $this->doc->createElement('div');
            $child = $this->doc->createElement('div');
            $illegalChildren = $this->getNonInsertableNodes();
            foreach ($illegalChildren as $node) {
                $this->wptAssertThrowsDom('NotFoundError', function () {
                    call_user_func('insertFunc', $node, $child);
                });
            }
        }, "Should check whether 'child' is a child of 'parent' before checking whether 'node' is of a type that can have a parent.");
        // Step 3 happens before step 5.
        $this->assertTest(function () {
            $child = $this->doc->createElement('div');
            $node = $this->doc->createTextNode('');
            $parent = $this->doc->implementation->createDocument(null, 'foo', null);
            $this->wptAssertThrowsDom('NotFoundError', function () {
                call_user_func('insertFunc', $node, $child);
            });
            $node = $this->doc->implementation->createDocumentType('html', '', '');
            foreach ($getNonDocumentParentNodes as $parent) {
                $this->wptAssertThrowsDom('NotFoundError', function () {
                    call_user_func('insertFunc', $node, $child);
                });
            }
        }, "Should check whether 'child' is a child of 'parent' before checking whether 'node' is of a type that can have a parent of the type that 'parent' is.");
        // Step 3 happens before step 6.
        $this->assertTest(function () {
            $child = $this->doc->createElement('div');
            $parent = $this->doc->implementation->createDocument(null, null, null);
            $node = $this->doc->createDocumentFragment();
            $node->appendChild($this->doc->createElement('div'));
            $node->appendChild($this->doc->createElement('div'));
            $this->wptAssertThrowsDom('NotFoundError', function () {
                call_user_func('insertFunc', $node, $child);
            });
            $node = $this->doc->createElement('div');
            $parent->appendChild($this->doc->createElement('div'));
            $this->wptAssertThrowsDom('NotFoundError', function () {
                call_user_func('insertFunc', $node, $child);
            });
            $parent->firstChild->remove();
            $parent->appendChild($this->doc->implementation->createDocumentType('html', '', ''));
            $node = $this->doc->implementation->createDocumentType('html', '', '');
            $this->wptAssertThrowsDom('NotFoundError', function () {
                call_user_func('insertFunc', $node, $child);
            });
        }, "Should check whether 'child' is a child of 'parent' before checking whether 'node' can be inserted into the document given the kids the document has right now.");
    }
}
