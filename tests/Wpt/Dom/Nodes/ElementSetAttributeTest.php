<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute.html.
class ElementSetAttributeTest extends WptTestHarness
{
    public function testElementSetAttribute()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute.html');
        $this->assertTest(function () {
            $el = $this->doc->createElement('p');
            $el->setAttributeNS('foo', 'x', 'first');
            $el->setAttributeNS('foo2', 'x', 'second');
            $el->setAttribute('x', 'changed');
            $this->assertEqualsData(count($el->attributes), 2);
            $this->assertEqualsData($el->getAttribute('x'), 'changed');
            $this->assertEqualsData($el->getAttributeNS('foo', 'x'), 'changed');
            $this->assertEqualsData($el->getAttributeNS('foo2', 'x'), 'second');
        }, 'setAttribute should change the first attribute, irrespective of namespace');
        $this->assertTest(function () {
            // https://github.com/whatwg/dom/issues/31
            $el = $this->doc->createElement('p');
            $el->setAttribute('FOO', 'bar');
            $this->assertEqualsData($el->getAttribute('foo'), 'bar');
            $this->assertEqualsData($el->getAttribute('FOO'), 'bar');
            $this->assertEqualsData($el->getAttributeNS('', 'foo'), 'bar');
            $this->assertEqualsData($el->getAttributeNS('', 'FOO'), null);
        }, 'setAttribute should lowercase before setting');
    }
}
