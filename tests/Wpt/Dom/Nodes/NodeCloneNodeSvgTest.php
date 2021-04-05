<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-svg.html.
class NodeCloneNodeSvgTest extends WptTestHarness
{
    public function testNodeCloneNodeSvg()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-svg.html';
        $svg = $this->doc->querySelector('svg');
        $clone = $svg->cloneNode(true);
        $this->assertTest(function () use(&$clone) {
            $this->assertEqualsData($clone->namespaceURI, 'http://www.w3.org/2000/svg');
            $this->assertEqualsData($clone->prefix, null);
            $this->assertEqualsData($clone->localName, 'svg');
            $this->assertEqualsData($clone->tagName, 'svg');
        }, 'cloned <svg> should have the right properties');
        $this->assertTest(function () use(&$clone) {
            $attr = $clone->attributes[0];
            $this->assertEqualsData($attr->namespaceURI, 'http://www.w3.org/2000/xmlns/');
            $this->assertEqualsData($attr->prefix, 'xmlns');
            $this->assertEqualsData($attr->localName, 'xlink');
            $this->assertEqualsData($attr->name, 'xmlns:xlink');
            $this->assertEqualsData($attr->value, 'http://www.w3.org/1999/xlink');
        }, "cloned <svg>'s xmlns:xlink attribute should have the right properties");
        $this->assertTest(function () use(&$clone) {
            $use = $clone->firstElementChild;
            $this->assertEqualsData($use->namespaceURI, 'http://www.w3.org/2000/svg');
            $this->assertEqualsData($use->prefix, null);
            $this->assertEqualsData($use->localName, 'use');
            $this->assertEqualsData($use->tagName, 'use');
        }, 'cloned <use> should have the right properties');
        $this->assertTest(function () use(&$clone) {
            $use = $clone->firstElementChild;
            $attr = $use->attributes[0];
            $this->assertEqualsData($attr->namespaceURI, 'http://www.w3.org/1999/xlink');
            $this->assertEqualsData($attr->prefix, 'xlink');
            $this->assertEqualsData($attr->localName, 'href');
            $this->assertEqualsData($attr->name, 'xlink:href');
            $this->assertEqualsData($attr->value, '#test');
        }, "cloned <use>'s xlink:href attribute should have the right properties");
    }
}
