<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-svg.html.
class NodeCloneNodeSvgTest extends WPTTestHarness
{
    public function testNodeCloneNodeSvg()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-svg.html');
        $svg = $this->doc->querySelector('svg');
        $clone = $svg->cloneNode(true);
        $this->assertTest(function () use(&$clone) {
            $this->wptAssertEquals($clone->namespaceURI, 'http://www.w3.org/2000/svg');
            $this->wptAssertEquals($clone->prefix, null);
            $this->wptAssertEquals($clone->localName, 'svg');
            $this->wptAssertEquals($clone->tagName, 'svg');
        }, 'cloned <svg> should have the right properties');
        $this->assertTest(function () use(&$clone) {
            $attr = $clone->attributes[0];
            $this->wptAssertEquals($attr->namespaceURI, 'http://www.w3.org/2000/xmlns/');
            $this->wptAssertEquals($attr->prefix, 'xmlns');
            $this->wptAssertEquals($attr->localName, 'xlink');
            $this->wptAssertEquals($attr->name, 'xmlns:xlink');
            $this->wptAssertEquals($attr->value, 'http://www.w3.org/1999/xlink');
        }, "cloned <svg>'s xmlns:xlink attribute should have the right properties");
        $this->assertTest(function () use(&$clone) {
            $use = $clone->firstElementChild;
            $this->wptAssertEquals($use->namespaceURI, 'http://www.w3.org/2000/svg');
            $this->wptAssertEquals($use->prefix, null);
            $this->wptAssertEquals($use->localName, 'use');
            $this->wptAssertEquals($use->tagName, 'use');
        }, 'cloned <use> should have the right properties');
        $this->assertTest(function () use(&$clone) {
            $use = $clone->firstElementChild;
            $attr = $use->attributes[0];
            $this->wptAssertEquals($attr->namespaceURI, 'http://www.w3.org/1999/xlink');
            $this->wptAssertEquals($attr->prefix, 'xlink');
            $this->wptAssertEquals($attr->localName, 'href');
            $this->wptAssertEquals($attr->name, 'xlink:href');
            $this->wptAssertEquals($attr->value, '#test');
        }, "cloned <use>'s xlink:href attribute should have the right properties");
    }
}
