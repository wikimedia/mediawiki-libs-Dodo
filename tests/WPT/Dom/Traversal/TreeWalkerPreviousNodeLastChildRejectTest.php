<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-previousNodeLastChildReject.html.
class TreeWalkerPreviousNodeLastChildRejectTest extends WPTTestHarness
{
    public function filter($node)
    {
        if ($node->id == 'C2') {
            return NodeFilter::FILTER_REJECT;
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
    public function testTreeWalkerPreviousNodeLastChildReject()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/TreeWalker-previousNodeLastChildReject.html');
        $testElement = null;
        // setup()
        $testElement = $this->doc->createElement('div');
        $testElement->id = 'root';
        // testElement.innerHTML='<div id="A1"><div id="B1"><div id="C1"></div><div id="C2"><div id="D1"></div><div id="D2"></div></div></div><div id="B2"><div id="C3"></div><div id="C4"></div></div></div>';
        // testElement.innerHTML='
        // <div id="A1">
        //   <div id="B1">
        //     <div id="C1">
        //     </div>
        //     <div id="C2">
        //       <div id="D1">
        //       </div>
        //       <div id="D2">
        //       </div>
        //     </div>
        //   </div>
        //   <div id="B2">
        //     <div id="C3">
        //     </div>
        //     <div id="C4">
        //     </div>
        //   </div>
        // </div>';
        // XXX for Servo, build the tree without using innerHTML
        $a1 = $this->doc->createElement('div');
        $a1->id = 'A1';
        $b1 = $this->doc->createElement('div');
        $b1->id = 'B1';
        $b2 = $this->doc->createElement('div');
        $b2->id = 'B2';
        $c1 = $this->doc->createElement('div');
        $c1->id = 'C1';
        $c2 = $this->doc->createElement('div');
        $c2->id = 'C2';
        $c3 = $this->doc->createElement('div');
        $c3->id = 'C3';
        $c4 = $this->doc->createElement('div');
        $c4->id = 'C4';
        $d1 = $this->doc->createElement('div');
        $d1->id = 'D1';
        $d2 = $this->doc->createElement('div');
        $d2->id = 'D2';
        $testElement->appendChild($a1);
        $a1->appendChild($b1);
        $a1->appendChild($b2);
        $b1->appendChild($c1);
        $b1->appendChild($c2);
        $b2->appendChild($c3);
        $b2->appendChild($c4);
        $c2->appendChild($d1);
        $c2->appendChild($d2);
        $this->assertTest(function () use(&$testElement) {
            $walker = $this->doc->createTreeWalker($testElement, NodeFilter::SHOW_ELEMENT, $filter);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'root']);
            $this->assertNodeData($walker->firstChild(), ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'A1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'C1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'C1']);
            $this->assertNodeData($walker->nextNode(), ['type' => Element, 'id' => 'B2']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'B2']);
            $this->assertNodeData($walker->previousNode(), ['type' => Element, 'id' => 'C1']);
            $this->assertNodeData($walker->currentNode, ['type' => Element, 'id' => 'C1']);
        }, 'Test that previousNode properly respects the filter.');
    }
}
