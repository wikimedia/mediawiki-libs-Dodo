<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\StaticRange;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/StaticRange-constructor.html.
class StaticRangeConstructorTest extends WPTTestHarness
{
    public function testStaticRangeConstructor()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/StaticRange-constructor.html');
        $this->getCommon()->testDiv = $this->doc->getElementById('testDiv');
        $testTextNode = $this->getCommon()->testDiv->firstChild;
        $testPINode = $this->doc->createProcessingInstruction('foo', 'abc');
        $testCommentNode = $this->doc->createComment('abc');
        $this->doc->body->append($testPINode, $testCommentNode);
        $this->assertTest(function ()  {
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 1, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->getCommon()->testDiv, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Element container');
        $this->assertTest(function () use (&$testTextNode) {
            $staticRange = new StaticRange(['startContainer' => $testTextNode, 'startOffset' => 1, 'endContainer' => $testTextNode, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $testTextNode, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testTextNode, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Text container');
        $this->assertTest(function () use (&$testTextNode) {
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endContainer' => $testTextNode, 'endOffset' => 1]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testTextNode, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 1, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Element startContainer and Text endContainer');
        $this->assertTest(function () use (&$testTextNode) {
            $staticRange = new StaticRange(['startContainer' => $testTextNode, 'startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 3]);
            $this->wptAssertEquals($staticRange->startContainer, $testTextNode, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->getCommon()->testDiv, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 3, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Text startContainer and Element endContainer');
        $this->assertTest(function () use (&$testPINode) {
            $staticRange = new StaticRange(['startContainer' => $testPINode, 'startOffset' => 1, 'endContainer' => $testPINode, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $testPINode, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testPINode, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with ProcessingInstruction container');
        $this->assertTest(function () use (&$testCommentNode) {
            $staticRange = new StaticRange(['startContainer' => $testCommentNode, 'startOffset' => 1, 'endContainer' => $testCommentNode, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $testCommentNode, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testCommentNode, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Comment container');
        $this->assertTest(function () {
            $xmlDoc = (new DOMParser())->parseFromString('<xml></xml>', 'application/xml');
            $testCDATASection = $xmlDoc->createCDATASection('abc');
            $staticRange = new StaticRange(['startContainer' => $testCDATASection, 'startOffset' => 1, 'endContainer' => $testCDATASection, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $testCDATASection, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testCDATASection, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with CDATASection container');
        $this->assertTest(function () {
            $staticRange = new StaticRange(['startContainer' => $this->doc, 'startOffset' => 0, 'endContainer' => $this->doc, 'endOffset' => 1]);
            $this->wptAssertEquals($staticRange->startContainer, $this->doc, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->doc, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 1, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with Document container');
        $this->assertTest(function () {
            $testDocFrag = $this->doc->createDocumentFragment();
            $testDocFrag->append('a', 'b', 'c');
            $staticRange = new StaticRange(['startContainer' => $testDocFrag, 'startOffset' => 0, 'endContainer' => $testDocFrag, 'endOffset' => 1]);
            $this->wptAssertEquals($staticRange->startContainer, $testDocFrag, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testDocFrag, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 1, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with DocumentFragment container');
        $this->assertTest(function ()  {
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 0]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->getCommon()->testDiv, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 0, 'valid endOffset');
            $this->wptAssertTrue($staticRange->collapsed, 'collapsed');
        }, 'Construct collapsed static range');
        $this->assertTest(function ()  {
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 1, 'endContainer' => $this->doc->body, 'endOffset' => 0]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->doc->body, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 0, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct inverted static range');
        $this->assertTest(function ()  {
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 15]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $this->getCommon()->testDiv, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 15, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with offset greater than length');
        $this->assertTest(function () {
            $testNode = $this->doc->createTextNode('abc');
            $staticRange = new StaticRange(['startContainer' => $testNode, 'startOffset' => 1, 'endContainer' => $testNode, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $testNode, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testNode, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with standalone Node container');
        $this->assertTest(function ()  {
            $testRoot = $this->doc->createElement('div');
            $testRoot->append('a', 'b');
            $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 1, 'endContainer' => $testRoot, 'endOffset' => 2]);
            $this->wptAssertEquals($staticRange->startContainer, $this->getCommon()->testDiv, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 1, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testRoot, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 2, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with endpoints in disconnected trees');
        $this->assertTest(function () {
            $testDocNode = $this->doc->implementation->createDocument('about:blank', 'html', null);
            $staticRange = new StaticRange(['startContainer' => $this->doc, 'startOffset' => 0, 'endContainer' => $testDocNode->documentElement, 'endOffset' => 0]);
            $this->wptAssertEquals($staticRange->startContainer, $this->doc, 'valid startContainer');
            $this->wptAssertEquals($staticRange->startOffset, 0, 'valid startOffset');
            $this->wptAssertEquals($staticRange->endContainer, $testDocNode->documentElement, 'valid endContainer');
            $this->wptAssertEquals($staticRange->endOffset, 0, 'valid endOffset');
            $this->wptAssertFalse($staticRange->collapsed, 'not collapsed');
        }, 'Construct static range with endpoints in disconnected documents');
        $this->assertTest(function ()  {
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function () {
                $staticRange = new StaticRange(['startContainer' => $this->doc->doctype, 'startOffset' => 0, 'endContainer' => $this->doc->doctype, 'endOffset' => 0]);
            }, 'throw a InvalidNodeTypeError when a DocumentType is passed as a startContainer or endContainer');
            $this->wptAssertThrowsDom('INVALID_NODE_TYPE_ERR', function ()  {
                $testAttrNode = $this->getCommon()->testDiv->getAttributeNode('id');
                $staticRange = new StaticRange(['startContainer' => $testAttrNode, 'startOffset' => 0, 'endContainer' => $testAttrNode, 'endOffset' => 0]);
            }, 'throw a InvalidNodeTypeError when a Attr is passed as a startContainer or endContainer');
        }, 'Throw on DocumentType or Attr container');
        $this->assertTest(function ()  {
            $this->wptAssertThrowsJs($this->type_error, function () {
                $staticRange = new StaticRange();
            }, 'throw a TypeError when no argument is passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a startContainer is not passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a startOffset is not passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endOffset' => 0]);
            }, 'throw a TypeError when an endContainer is not passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv]);
            }, 'throw a TypeError when an endOffset is not passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startContainer' => null, 'startOffset' => 0, 'endContainer' => $this->getCommon()->testDiv, 'endOffset' => 0]);
            }, 'throw a TypeError when a null startContainer is passed');
            $this->wptAssertThrowsJs($this->type_error, function ()  {
                $staticRange = new StaticRange(['startContainer' => $this->getCommon()->testDiv, 'startOffset' => 0, 'endContainer' => null, 'endOffset' => 0]);
            }, 'throw a TypeError when a null endContainer is passed');
        }, 'Throw on missing or invalid arguments');
    }
}
