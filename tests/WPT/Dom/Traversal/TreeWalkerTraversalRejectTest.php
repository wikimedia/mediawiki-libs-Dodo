<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Traversal;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-traversal-reject.html.
class TreeWalkerTraversalRejectTest extends WPTTestHarness
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
    public function testTreeWalkerTraversalReject()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-traversal-reject.html');
        $testElement = null;
        // setup()
        $testElement = $this->doc->createElement('div');
        $testElement->id = 'root';
        //testElement.innerHTML='<div id="A1">  <div id="B1">  <div id="C1"></div>  </div>  <div id="B2"></div><div id="B3"></div>  </div>';
        // <div id="A1">
        //   <div id="B1">
        //     <div id="C1"></div>
        //   </div>
        //   <div id="B2"></div>
        //   <div id="B3"></div>
        // </div>
        // XXX for Servo, build the tree without using innerHTML
        $a1 = $this->doc->createElement('div');
        $a1->id = 'A1';
        $b1 = $this->doc->createElement('div');
        $b1->id = 'B1';
        $b2 = $this->doc->createElement('div');
        $b2->id = 'B2';
        $b3 = $this->doc->createElement('div');
        $b3->id = 'B3';
        $c1 = $this->doc->createElement('div');
        $c1->id = 'C1';
        $testElement->appendChild($a1);
        $a1->appendChild($b1);
        $a1->appendChild($b2);
        $a1->appendChild($b3);
        $b1->appendChild($c1);
        $rejectB1Filter = ['acceptNode' => function ($node) {
            if ($node->id == 'B1') {
                return NodeFilter::FILTER_REJECT;
            }
            return NodeFilter::FILTER_ACCEPT;
        }];
        $skipB2Filter = ['acceptNode' => function ($node) {
            if ($node->id == 'B2') {
                return NodeFilter::FILTER_SKIP;
            }
            return NodeFilter::FILTER_ACCEPT;
        }];
        $this->assertTest(function () use (&$testElement, &$rejectB1Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $rejectB1Filter);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->wptAssertNode($walker->nextNode(), ['type' => Element, 'id' => 'B3']);
        }, 'Testing nextNode');
        $this->assertTest(function () use (&$testElement, &$rejectB1Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $rejectB1Filter);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'B2']);
        }, 'Testing firstChild');
        $this->assertTest(function () use (&$testElement, &$skipB2Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $skipB2Filter);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->wptAssertNode($walker->firstChild(), ['type' => Element, 'id' => 'B1']);
            $this->wptAssertNode($walker->nextSibling(), ['type' => Element, 'id' => 'B3']);
        }, 'Testing nextSibling');
        $this->assertTest(function () use (&$testElement, &$rejectB1Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $rejectB1Filter);
            $walker->currentNode = $testElement->querySelectorAll('#C1')[0];
            $this->wptAssertNode($walker->parentNode(), ['type' => Element, 'id' => 'A1']);
        }, 'Testing parentNode');
        $this->assertTest(function () use (&$testElement, &$skipB2Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $skipB2Filter);
            $walker->currentNode = $testElement->querySelectorAll('#B3')[0];
            $this->wptAssertNode($walker->getPreviousSibling()(), ['type' => Element, 'id' => 'B1']);
        }, 'Testing previousSibling');
        $this->assertTest(function () use (&$testElement, &$rejectB1Filter) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $rejectB1Filter);
            $walker->currentNode = $testElement->querySelectorAll('#B3')[0];
            $this->wptAssertNode($walker->previousNode(), ['type' => Element, 'id' => 'B2']);
            $this->wptAssertNode($walker->previousNode(), ['type' => Element, 'id' => 'A1']);
        }, 'Testing previousNode');
    }
}
