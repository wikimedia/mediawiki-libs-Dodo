<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild-namespace.html.
class ElementFirstElementChildNamespaceTest extends WPTTestHarness
{
    public function testElementFirstElementChildNamespace()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild-namespace.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $el = $this->doc->createElementNS('http://ns.example.org/pickle', 'pickle:dill');
            $el->setAttribute('id', 'first_element_child');
            $parentEl->appendChild($el);
            $fec = $parentEl->firstElementChild;
            $this->wptAssertTrue(!!$fec);
            $this->wptAssertEquals($fec->nodeType, 1);
            $this->wptAssertEquals($fec->getAttribute('id'), 'first_element_child');
            $this->wptAssertEquals($fec->localName, 'dill');
        });
    }
}
