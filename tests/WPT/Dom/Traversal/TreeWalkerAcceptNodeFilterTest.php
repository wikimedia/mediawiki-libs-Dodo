<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Traversal;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-acceptNode-filter.html.
class TreeWalkerAcceptNodeFilterTest extends WPTTestHarness
{
    public function filter($node)
    {
        if ($node->id == 'B1') {
            return NodeFilter::FILTER_SKIP;
        }
        return NodeFilter::FILTER_ACCEPT;
    }
    public function assertNode($actual, $expected)
    {
        $this->wptAssertTrue($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType);
        if (gettype($expected->id) !== NULL) {
            $this->wptAssertEquals($actual->id, $expected->id);
        }
        if (gettype($expected->nodeValue) !== NULL) {
            $this->wptAssertEquals($actual->nodeValue, $expected->nodeValue);
        }
    }
    public function testTreeWalkerAcceptNodeFilter()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-acceptNode-filter.html');
        $testElement = null;
        // setup()
        $testElement = $this->doc->createElement('div');
        $testElement->id = 'root';
        //testElement.innerHTML='<div id="A1"><div id="B1"></div><div id="B2"></div></div>';
        // XXX for Servo, build the tree without using innerHTML
        $a1 = $this->doc->createElement('div');
        $a1->id = 'A1';
        $b1 = $this->doc->createElement('div');
        $b1->id = 'B1';
        $b2 = $this->doc->createElement('div');
        $b2->id = 'B2';
        $testElement->appendChild($a1);
        $a1->appendChild($b1);
        $a1->appendChild($b2);
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'B2']);
        }, 'Testing with raw function filter');
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function ($node) {
                if ($node->id == 'B1') {
                    return NodeFilter::FILTER_SKIP;
                }
                return NodeFilter::FILTER_ACCEPT;
            }]);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'B2']);
        }, 'Testing with object filter');
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, null);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'B1']);
        }, 'Testing with null filter');
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, null);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'B1']);
        }, 'Testing with undefined filter');
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, []);
            $this->wptAssertThrowsJs($this->type_error, function () use (&$walker) {
                $walker->firstChild();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertThrowsJs($this->type_error, function () use (&$walker) {
                $walker->nextNode();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with object lacking acceptNode property');
        $this->assertTest(function () use (&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => 'foo']);
            $this->wptAssertThrowsJs($this->type_error, function () use (&$walker) {
                $walker->firstChild();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertThrowsJs($this->type_error, function () use (&$walker) {
                $walker->nextNode();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with object with non-function acceptNode property');
        $this->assertTest(function ($t) use (&$testElement) {
            $filter = function () {
                return NodeFilter::FILTER_ACCEPT;
            };
            $filter->acceptNode = $t->unreached_func('`acceptNode` method should not be called on functions');
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
        }, 'Testing with function having acceptNode function');
        $this->assertTest(function () use (&$testElement) {
            $test_error = ['name' => 'test'];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, function ($node) use (&$test_error) {
                throw $test_error;
            });
            $this->wptAssertThrowsExactly($test_error, function () use (&$walker) {
                $walker->firstChild();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertThrowsExactly($test_error, function () use (&$walker) {
                $walker->nextNode();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with filter function that throws');
        $this->assertTest(function () use (&$testElement) {
            $testError = ['name' => 'test'];
            $filter = ['acceptNode' => function () use (&$testError) {
                throw $testError;
            }];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->wptAssertThrowsExactly($testError, function () use (&$walker) {
                $walker->firstChild();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertThrowsExactly($testError, function () use (&$walker) {
                $walker->nextNode();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'rethrows errors when getting `acceptNode`');
        $this->assertTest(function () use (&$testElement) {
            $calls = 0;
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function () use (&$calls) {
                $calls++;
                return function () {
                    return NodeFilter::FILTER_ACCEPT;
                };
            }]);
            $this->wptAssertEquals($calls, 0);
            $walker->nextNode();
            $walker->nextNode();
            $this->wptAssertEquals($calls, 2);
        }, 'performs `Get` on every traverse');
        $this->assertTest(function () use (&$testElement) {
            $test_error = ['name' => 'test'];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function ($node) use (&$test_error) {
                throw $test_error;
            }]);
            $this->wptAssertThrowsExactly($test_error, function () use (&$walker) {
                $walker->firstChild();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->wptAssertThrowsExactly($test_error, function () use (&$walker) {
                $walker->nextNode();
            });
            $this->wptAssertNode($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with filter object that throws');
        $this->assertTest(function () use (&$testElement) {
            $thisValue = null;
            $nodeArgID = null;
            $filter = ['acceptNode' => function ($node) {
                $thisValue = $this;
                $nodeArgID = $node->id;
                return NodeFilter::FILTER_ACCEPT;
            }];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $walker->nextNode();
            $this->wptAssertEquals($thisValue, $filter);
            $this->wptAssertEquals($nodeArgID, 'A1');
        }, 'Testing with filter object: this value and `node` argument');
    }
}
