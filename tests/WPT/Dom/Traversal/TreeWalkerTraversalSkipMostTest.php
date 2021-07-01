<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-traversal-skip-most.html.
class TreeWalkerTraversalSkipMostTest extends WPTTestHarness
{
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
    public function testTreeWalkerTraversalSkipMost()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-traversal-skip-most.html');
        $testElement = null;
        // setup()
        $testElement = $this->doc->createElement('div');
        $testElement->id = 'root';
        // testElement.innerHTML='<div id="A1"><div id="B1" class="keep"></div><div id="B2">this text matters</div><div id="B3" class="keep"></div></div>';
        // <div id="A1">
        //   <div id="B1" class="keep"></div>
        //   <div id="B2">this text matters</div>
        //   <div id="B3" class="keep"></div>
        // </div>
        // XXX for Servo, build the tree without using innerHTML
        $a1 = $this->doc->createElement('div');
        $a1->id = 'A1';
        $b1 = $this->doc->createElement('div');
        $b1->id = 'B1';
        $b1->className = 'keep';
        $b2 = $this->doc->createElement('div');
        $b2->id = 'B2';
        $b3 = $this->doc->createElement('div');
        $b3->id = 'B3';
        $b3->className = 'keep';
        $testElement->appendChild($a1);
        $a1->appendChild($b1);
        $a1->appendChild($b2)->appendChild($this->doc->createTextNode('this text matters'));
        $a1->appendChild($b3);
        $filter = ['acceptNode' => function ($node) {
            if ($node->className == 'keep') {
                return NodeFilter::FILTER_ACCEPT;
            }
            return NodeFilter::FILTER_SKIP;
        }];
        $this->assertTest(function () use(&$testElement, &$filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            assert_node($walker->firstChild(), ['type' => Element, 'id' => 'B1']);
            assert_node($walker->nextSibling(), ['type' => Element, 'id' => 'B3']);
        }, 'Testing nextSibling');
        $this->assertTest(function () use(&$testElement, &$filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $walker->currentNode = $testElement->querySelectorAll('#B3')[0];
            assert_node($walker->getPreviousSibling()(), ['type' => Element, 'id' => 'B1']);
        }, 'Testing previousSibling');
    }
}
