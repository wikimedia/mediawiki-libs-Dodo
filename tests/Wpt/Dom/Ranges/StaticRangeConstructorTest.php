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
// @see vendor/web-platform-tests/wpt/dom/ranges/StaticRange-constructor.html.
class StaticRangeConstructorTest extends WptTestHarness
{
    public function testStaticRangeConstructor()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/ranges/StaticRange-constructor.html';
        $testDiv = $this->doc->getElementById('testDiv');
        $testTextNode = $testDiv->firstChild;
        $testPINode = $this->doc->createProcessingInstruction('foo', 'abc');
        $testCommentNode = $this->doc->createComment('abc');
        $this->doc->body->append($testPINode, $testCommentNode);
        $this->assertTest(function () use(&$testDiv) {
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 1, 'endContainer' => $testDiv, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDiv, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Element container');
        $this->assertTest(function () use(&$testTextNode) {
            $staticRange = new StaticRange(['startContainer' => $testTextNode, 'startOffset' => 1, 'endContainer' => $testTextNode, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testTextNode, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testTextNode, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Text container');
        $this->assertTest(function () use(&$testDiv, &$testTextNode) {
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endContainer' => $testTextNode, 'endOffset' => 1]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testTextNode, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 1, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Element startContainer and Text endContainer');
        $this->assertTest(function () use(&$testTextNode, &$testDiv) {
            $staticRange = new StaticRange(['startContainer' => $testTextNode, 'startOffset' => 0, 'endContainer' => $testDiv, 'endOffset' => 3]);
            $this->assertEqualsData($staticRange->startContainer, $testTextNode, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDiv, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 3, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Text startContainer and Element endContainer');
        $this->assertTest(function () use(&$testPINode) {
            $staticRange = new StaticRange(['startContainer' => $testPINode, 'startOffset' => 1, 'endContainer' => $testPINode, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testPINode, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testPINode, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with ProcessingInstruction container');
        $this->assertTest(function () use(&$testCommentNode) {
            $staticRange = new StaticRange(['startContainer' => $testCommentNode, 'startOffset' => 1, 'endContainer' => $testCommentNode, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testCommentNode, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testCommentNode, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Comment container');
        $this->assertTest(function () {
            $xmlDoc = $this->parseFromString('<xml></xml>', 'application/xml');
            $testCDATASection = $xmlDoc->createCDATASection('abc');
            $staticRange = new StaticRange(['startContainer' => $testCDATASection, 'startOffset' => 1, 'endContainer' => $testCDATASection, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testCDATASection, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testCDATASection, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with CDATASection container');
        $this->assertTest(function () {
            $staticRange = new StaticRange(['startContainer' => $this->doc, 'startOffset' => 0, 'endContainer' => $this->doc, 'endOffset' => 1]);
            $this->assertEqualsData($staticRange->startContainer, $this->doc, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $this->doc, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 1, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Document container');
        $this->assertTest(function () {
            $testDocFrag = $this->doc->createDocumentFragment();
            $testDocFrag->append('a', 'b', 'c');
            $staticRange = new StaticRange(['startContainer' => $testDocFrag, 'startOffset' => 0, 'endContainer' => $testDocFrag, 'endOffset' => 1]);
            $this->assertEqualsData($staticRange->startContainer, $testDocFrag, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDocFrag, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 1, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with DocumentFragment container');
        $this->assertTest(function () use(&$testDiv) {
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endContainer' => $testDiv, 'endOffset' => 0]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDiv, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 0, 'valid endOffset');
            $this->assertTrueData($staticRange->collapsed, 'collapsed');
        }, 'Construct collapsed static range');
        $this->assertTest(function () use(&$testDiv) {
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 1, 'endContainer' => $this->doc->body, 'endOffset' => 0]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $this->doc->body, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 0, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct inverted static range');
        $this->assertTest(function () use(&$testDiv) {
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endContainer' => $testDiv, 'endOffset' => 15]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDiv, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 15, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with offset greater than length');
        $this->assertTest(function () {
            $testNode = $this->doc->createTextNode('abc');
            $staticRange = new StaticRange(['startContainer' => $testNode, 'startOffset' => 1, 'endContainer' => $testNode, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testNode, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testNode, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with standalone Node container');
        $this->assertTest(function () use(&$testDiv) {
            $testRoot = $this->doc->createElement('div');
            $testRoot->append('a', 'b');
            $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 1, 'endContainer' => $testRoot, 'endOffset' => 2]);
            $this->assertEqualsData($staticRange->startContainer, $testDiv, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 1, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testRoot, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 2, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with endpoints in disconnected trees');
        $this->assertTest(function () {
            $testDocNode = $this->doc->implementation->createDocument('about:blank', 'html', null);
            $staticRange = new StaticRange(['startContainer' => $this->doc, 'startOffset' => 0, 'endContainer' => $testDocNode->documentElement, 'endOffset' => 0]);
            $this->assertEqualsData($staticRange->startContainer, $this->doc, 'valid startContainer');
            $this->assertEqualsData($staticRange->startOffset, 0, 'valid startOffset');
            $this->assertEqualsData($staticRange->endContainer, $testDocNode->documentElement, 'valid endContainer');
            $this->assertEqualsData($staticRange->endOffset, 0, 'valid endOffset');
            $this->assertFalseData($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with endpoints in disconnected documents');
        $this->assertTest(function () use(&$testDiv) {
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () {
                $staticRange = new StaticRange(['startContainer' => $this->doc->doctype, 'startOffset' => 0, 'endContainer' => $this->doc->doctype, 'endOffset' => 0]);
            }, 'throw a InvalidNodeTypeError when a DocumentType is passed as a startContainer or endContainer');
            $this->assertThrowsDomData('INVALID_NODE_TYPE_ERR', function () use(&$testDiv) {
                $testAttrNode = $testDiv->getAttributeNode('id');
                $staticRange = new StaticRange(['startContainer' => $testAttrNode, 'startOffset' => 0, 'endContainer' => $testAttrNode, 'endOffset' => 0]);
            }, 'throw a InvalidNodeTypeError when a Attr is passed as a startContainer or endContainer');
        }, 'Throw on DocumentType or Attr container');
        $this->assertTest(function () use(&$testDiv) {
            $this->assertThrowsJsData($this->type_error, function () {
                $staticRange = new StaticRange();
            }, 'throw a TypeError when no argument is passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startOffset' => 0, 'endContainer' => $testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a startContainer is not passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startContainer' => $testDiv, 'endContainer' => $testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a startOffset is not passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endOffset' => 0]);
            }, 'throw a TypeError when an endContainer is not passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endContainer' => $testDiv]);
            }, 'throw a TypeError when an endOffset is not passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startContainer' => null, 'startOffset' => 0, 'endContainer' => $testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a null startContainer is passed');
            $this->assertThrowsJsData($this->type_error, function () use(&$testDiv) {
                $staticRange = new StaticRange(['startContainer' => $testDiv, 'startOffset' => 0, 'endContainer' => null, 'endOffset' => 0]);
            }, 'throw a TypeError when a null endContainer is passed');
        }, 'Throw on missing or invalid arguments');
    }
}
