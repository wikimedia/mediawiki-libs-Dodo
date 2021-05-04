<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttribute.html.
class ElementRemoveAttributeTest extends WptTestHarness
{
    public function testElementRemoveAttribute()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttribute.html');
        $this->assertTest(function () {
            $el = $this->doc->createElement('p');
            $el->setAttribute('x', 'first');
            $el->setAttributeNS('foo', 'x', 'second');
            $this->assertEqualsData(count($el->attributes), 2);
            $this->assertEqualsData($el->getAttribute('x'), 'first');
            $this->assertEqualsData($el->getAttributeNS(null, 'x'), 'first');
            $this->assertEqualsData($el->getAttributeNS('foo', 'x'), 'second');
            // removeAttribute removes the first attribute with name "x" that
            // we set on the element, irrespective of namespace.
            $el->removeAttribute('x');
            // The only attribute remaining should be the second one.
            $this->assertEqualsData($el->getAttribute('x'), 'second');
            $this->assertEqualsData($el->getAttributeNS(null, 'x'), null);
            $this->assertEqualsData($el->getAttributeNS('foo', 'x'), 'second');
            $this->assertEqualsData(count($el->attributes), 1, 'one attribute');
        }, 'removeAttribute should remove the first attribute, irrespective of namespace, when the first attribute is ' . 'not in a namespace');
        $this->assertTest(function () {
            $el = $this->doc->createElement('p');
            $el->setAttributeNS('foo', 'x', 'first');
            $el->setAttributeNS('foo2', 'x', 'second');
            $this->assertEqualsData(count($el->attributes), 2);
            $this->assertEqualsData($el->getAttribute('x'), 'first');
            $this->assertEqualsData($el->getAttributeNS('foo', 'x'), 'first');
            $this->assertEqualsData($el->getAttributeNS('foo2', 'x'), 'second');
            // removeAttribute removes the first attribute with name "x" that
            // we set on the element, irrespective of namespace.
            $el->removeAttribute('x');
            // The only attribute remaining should be the second one.
            $this->assertEqualsData($el->getAttribute('x'), 'second');
            $this->assertEqualsData($el->getAttributeNS('foo', 'x'), null);
            $this->assertEqualsData($el->getAttributeNS('foo2', 'x'), 'second');
            $this->assertEqualsData(count($el->attributes), 1, 'one attribute');
        }, 'removeAttribute should remove the first attribute, irrespective of namespace, when the first attribute is ' . 'in a namespace');
    }
}
