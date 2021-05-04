<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-selectNode.html.
class RangeSelectNodeTest extends WptTestHarness
{
    public function testSelectNode($range, $node)
    {
        try {
            $range->collapsed;
        } catch (Exception $e) {
            // Range is detached
            $this->assertThrowsDomData('INVALID_STATE_ERR', function () use(&$range, &$node) {
                $range->selectNode($node);
            }, 'selectNode() on a detached node must throw INVALID_STATE_ERR');
            $this->assertThrowsDomData('INVALID_STATE_ERR', function () use(&$range, &$node) {
                $range->selectNodeContents($node);
            }, 'selectNodeContents() on a detached node must throw INVALID_STATE_ERR');
            return;
        }
        if (!$node->parentNode) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->selectNode($node);
            }, 'selectNode() on a node with no parent must throw INVALID_NODE_TYPE_ERR');
        } else {
            $index = 0;
            while ($node->parentNode->childNodes[$index] != $node) {
                $index++;
            }
            $range->selectNode($node);
            $this->assertEqualsData($range->startContainer, $node->parentNode, 'After selectNode(), startContainer must equal parent node');
            $this->assertEqualsData($range->endContainer, $node->parentNode, 'After selectNode(), endContainer must equal parent node');
            $this->assertEqualsData($range->startOffset, $index, 'After selectNode(), startOffset must be index of node in parent (' . $index . ')');
            $this->assertEqualsData($range->endOffset, $index + 1, 'After selectNode(), endOffset must be one plus index of node in parent (' . ($index + 1) . ')');
        }
        if ($node->nodeType == Node::DOCUMENT_TYPE_NODE) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$range, &$node) {
                $range->selectNodeContents($node);
            }, 'selectNodeContents() on a doctype must throw INVALID_NODE_TYPE_ERR');
        } else {
            $range->selectNodeContents($node);
            $this->assertEqualsData($range->startContainer, $node, 'After selectNodeContents(), startContainer must equal node');
            $this->assertEqualsData($range->endContainer, $node, 'After selectNodeContents(), endContainer must equal node');
            $this->assertEqualsData($range->startOffset, 0, 'After selectNodeContents(), startOffset must equal 0');
            $len = nodeLength($node);
            $this->assertEqualsData($range->endOffset, $len, 'After selectNodeContents(), endOffset must equal node length (' . $len . ')');
        }
    }
    public function testTree($root, $marker)
    {
        global $tests;
        if ($root->nodeType == Node::ELEMENT_NODE && $root->id == 'log') {
            // This is being modified during the tests, so let's not test it.
            return;
        }
        $tests[] = [$marker . ': ' . strtolower($root->nodeName) . " node, current doc's range, type " . $root->nodeType, $range, $root];
        $tests[] = [$marker . ': ' . strtolower($root->nodeName) . " node, foreign doc's range, type " . $root->nodeType, $foreignRange, $root];
        $tests[] = [$marker . ': ' . strtolower($root->nodeName) . " node, XML doc's range, type " . $root->nodeType, $xmlRange, $root];
        $tests[] = [$marker . ': ' . strtolower($root->nodeName) . ' node, detached range, type ' . $root->nodeType, $detachedRange, $root];
        for ($i = 0; $i < count($root->childNodes); $i++) {
            $this->testTree($root->childNodes[$i], $marker . '[' . $i . ']');
        }
    }
    public function testRangeSelectNode()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-selectNode.html');
        $range = $this->doc->createRange();
        $foreignRange = $foreignDoc->createRange();
        $xmlRange = $xmlDoc->createRange();
        $detachedRange = $this->doc->createRange();
        $detachedRange->detach();
        $tests = [];
        $this->testTree($this->doc, 'current doc');
        $this->testTree($foreignDoc, 'foreign doc');
        $this->testTree($detachedDiv, 'detached div in current doc');
        $otherTests = ['xmlDoc', 'xmlElement', 'detachedTextNode', 'foreignTextNode', 'xmlTextNode', 'processingInstruction', 'comment', 'foreignComment', 'xmlComment', 'docfrag', 'foreignDocfrag', 'xmlDocfrag'];
        for ($i = 0; $i < count($otherTests); $i++) {
            $this->testTree($this->window[$otherTests[$i]], $otherTests[$i]);
        }
        $this->generateTests($testSelectNode, $tests);
        $testDiv->style->display = 'none';
    }
}
