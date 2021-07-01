<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-nodeName.html.
class NodeNodeNameTest extends WPTTestHarness
{
    public function testNodeNodeName()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-nodeName.html');
        $this->assertTest(function () {
            $HTMLNS = 'http://www.w3.org/1999/xhtml';
            $SVGNS = 'http://www.w3.org/2000/svg';
            $this->wptAssertEquals($this->doc->createElementNS($HTMLNS, 'I')->nodeName, 'I');
            $this->wptAssertEquals($this->doc->createElementNS($HTMLNS, 'i')->nodeName, 'I');
            $this->wptAssertEquals($this->doc->createElementNS($SVGNS, 'svg')->nodeName, 'svg');
            $this->wptAssertEquals($this->doc->createElementNS($SVGNS, 'SVG')->nodeName, 'SVG');
            $this->wptAssertEquals($this->doc->createElementNS($HTMLNS, 'x:b')->nodeName, 'X:B');
        }, 'For Element nodes, nodeName should return the same as tagName.');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createTextNode('foo')->nodeName, '#text');
        }, 'For Text nodes, nodeName should return "#text".');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createComment('foo')->nodeName, '#comment');
        }, 'For Comment nodes, nodeName should return "#comment".');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->nodeName, '#document');
        }, 'For Document nodes, nodeName should return "#document".');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->doctype->nodeName, 'html');
        }, 'For DocumentType nodes, nodeName should return the name.');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->doc->createDocumentFragment()->nodeName, '#document-fragment');
        }, 'For DocumentFragment nodes, nodeName should return "#document-fragment".');
    }
}
