<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute.html.
class ElementSetAttributeTest extends WPTTestHarness
{
    public function testElementSetAttribute()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute.html');
        $this->assertTest(function () {
            $el = $this->doc->createElement('p');
            $el->setAttributeNS('foo', 'x', 'first');
            $el->setAttributeNS('foo2', 'x', 'second');
            $el->setAttribute('x', 'changed');
            $this->wptAssertEquals(count($el->attributes), 2);
            $this->wptAssertEquals($el->getAttribute('x'), 'changed');
            $this->wptAssertEquals($el->getAttributeNS('foo', 'x'), 'changed');
            $this->wptAssertEquals($el->getAttributeNS('foo2', 'x'), 'second');
        }, 'setAttribute should change the first attribute, irrespective of namespace');
        $this->assertTest(function () {
            // https://github.com/whatwg/dom/issues/31
            $el = $this->doc->createElement('p');
            $el->setAttribute('FOO', 'bar');
            $this->wptAssertEquals($el->getAttribute('foo'), 'bar');
            $this->wptAssertEquals($el->getAttribute('FOO'), 'bar');
            $this->wptAssertEquals($el->getAttributeNS('', 'foo'), 'bar');
            $this->wptAssertEquals($el->getAttributeNS('', 'FOO'), null);
        }, 'setAttribute should lowercase before setting');
    }
}
