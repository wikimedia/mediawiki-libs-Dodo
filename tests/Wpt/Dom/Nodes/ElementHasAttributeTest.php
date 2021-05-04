<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttribute.html.
class ElementHasAttributeTest extends WptTestHarness
{
    public function testElementHasAttribute()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttribute.html');
        $this->assertTest(function () {
            $el = $this->doc->createElement('p');
            $el->setAttributeNS('foo', 'x', 'first');
            $this->assertTrueData($el->hasAttribute('x'));
        }, 'hasAttribute should check for attribute presence, irrespective of namespace');
        $this->assertTest(function () {
            $el = $this->doc->getElementById('t');
            $this->assertTrueData($el->hasAttribute('data-e2'));
            $this->assertTrueData($el->hasAttribute('data-E2'));
            $this->assertTrueData($el->hasAttribute('data-f2'));
            $this->assertTrueData($el->hasAttribute('data-F2'));
        }, 'hasAttribute should work with all attribute casings');
    }
}
