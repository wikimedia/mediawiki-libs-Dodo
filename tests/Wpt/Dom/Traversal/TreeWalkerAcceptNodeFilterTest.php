<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-acceptNode-filter.html.
class TreeWalkerAcceptNodeFilterTest extends WptTestHarness
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
        $this->assertTrueData($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType);
        if (gettype($expected->id) !== NULL) {
            $this->assertEqualsData($actual->id, $expected->id);
        }
        if (gettype($expected->nodeValue) !== NULL) {
            $this->assertEqualsData($actual->nodeValue, $expected->nodeValue);
        }
    }
    public function testTreeWalkerAcceptNodeFilter()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-acceptNode-filter.html';
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
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B2']);
        }, 'Testing with raw function filter');
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function ($node) {
                if ($node->id == 'B1') {
                    return NodeFilter::FILTER_SKIP;
                }
                return NodeFilter::FILTER_ACCEPT;
            }]);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B2']);
        }, 'Testing with object filter');
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, null);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B1']);
        }, 'Testing with null filter');
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, null);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B1']);
        }, 'Testing with undefined filter');
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, []);
            $this->assertThrowsJsData($this->type_error, function () use(&$walker) {
                $walker->firstChild();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertThrowsJsData($this->type_error, function () use(&$walker) {
                $walker->nextNode();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with object lacking acceptNode property');
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => 'foo']);
            $this->assertThrowsJsData($this->type_error, function () use(&$walker) {
                $walker->firstChild();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertThrowsJsData($this->type_error, function () use(&$walker) {
                $walker->nextNode();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with object with non-function acceptNode property');
        $this->assertTest(function ($t) use(&$testElement) {
            $filter = function () {
                return NodeFilter::FILTER_ACCEPT;
            };
            $filter->acceptNode = $t->unreached_func('`acceptNode` method should not be called on functions');
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
        }, 'Testing with function having acceptNode function');
        $this->assertTest(function () use(&$testElement) {
            $test_error = ['name' => 'test'];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, function ($node) use(&$test_error) {
                throw $test_error;
            });
            $this->assertThrowsExactlyData($test_error, function () use(&$walker) {
                $walker->firstChild();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertThrowsExactlyData($test_error, function () use(&$walker) {
                $walker->nextNode();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with filter function that throws');
        $this->assertTest(function () use(&$testElement) {
            $testError = ['name' => 'test'];
            $filter = ['acceptNode' => function () use(&$testError) {
                throw $testError;
            }];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->assertThrowsExactlyData($testError, function () use(&$walker) {
                $walker->firstChild();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertThrowsExactlyData($testError, function () use(&$walker) {
                $walker->nextNode();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'rethrows errors when getting `acceptNode`');
        $this->assertTest(function () use(&$testElement) {
            $calls = 0;
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function () use(&$calls) {
                $calls++;
                return function () {
                    return NodeFilter::FILTER_ACCEPT;
                };
            }]);
            $this->assertEqualsData($calls, 0);
            $walker->nextNode();
            $walker->nextNode();
            $this->assertEqualsData($calls, 2);
        }, 'performs `Get` on every traverse');
        $this->assertTest(function () use(&$testElement) {
            $test_error = ['name' => 'test'];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, ['acceptNode' => function ($node) use(&$test_error) {
                throw $test_error;
            }]);
            $this->assertThrowsExactlyData($test_error, function () use(&$walker) {
                $walker->firstChild();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertThrowsExactlyData($test_error, function () use(&$walker) {
                $walker->nextNode();
            });
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
        }, 'Testing with filter object that throws');
        $this->assertTest(function () use(&$testElement) {
            $thisValue = null;
            $nodeArgID = null;
            $filter = ['acceptNode' => function ($node) {
                $thisValue = $this;
                $nodeArgID = $node->id;
                return NodeFilter::FILTER_ACCEPT;
            }];
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $walker->nextNode();
            $this->assertEqualsData($thisValue, $filter);
            $this->assertEqualsData($nodeArgID, 'A1');
        }, 'Testing with filter object: this value and `node` argument');
    }
}
