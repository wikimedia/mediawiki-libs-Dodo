<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild-namespace.html.
class ElementFirstElementChildNamespaceTest extends WptTestHarness
{
    public function testElementFirstElementChildNamespace()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-firstElementChild-namespace.html');
        $this->assertTest(function () {
            $parentEl = $this->doc->getElementById('parentEl');
            $el = $this->doc->createElementNS('http://ns.example.org/pickle', 'pickle:dill');
            $el->setAttribute('id', 'first_element_child');
            $parentEl->appendChild($el);
            $fec = $parentEl->firstElementChild;
            $this->assertTrueData(!!$fec);
            $this->assertEqualsData($fec->nodeType, 1);
            $this->assertEqualsData($fec->getAttribute('id'), 'first_element_child');
            $this->assertEqualsData($fec->localName, 'dill');
        });
    }
}
