<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttributes.html.
class ElementHasAttributesTest extends WptTestHarness
{
    public function testElementHasAttributes()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttributes.html';
        $this->assertTest(function () {
            $buttonElement = $this->doc->getElementsByTagName('button')[0];
            $this->assertEqualsData($buttonElement->hasAttributes(), false, 'hasAttributes() on empty element must return false.');
            $emptyDiv = $this->doc->createElement('div');
            $this->assertEqualsData($emptyDiv->hasAttributes(), false, 'hasAttributes() on dynamically created empty element must return false.');
        }, 'element.hasAttributes() must return false when the element does not have attribute.');
        $this->assertTest(function () {
            $divWithId = $this->doc->getElementById('foo');
            $this->assertEqualsData($divWithId->hasAttributes(), true, 'hasAttributes() on element with id attribute must return true.');
            $divWithClass = $this->doc->createElement('div');
            $divWithClass->setAttribute('class', 'foo');
            $this->assertEqualsData($divWithClass->hasAttributes(), true, 'hasAttributes() on dynamically created element with class attribute must return true.');
            $pWithCustomAttr = $this->doc->getElementsByTagName('p')[0];
            $this->assertEqualsData($pWithCustomAttr->hasAttributes(), true, 'hasAttributes() on element with custom attribute must return true.');
            $divWithCustomAttr = $this->doc->createElement('div');
            $divWithCustomAttr->setAttribute('data-custom', 'foo');
            $this->assertEqualsData($divWithCustomAttr->hasAttributes(), true, 'hasAttributes() on dynamically created element with custom attribute must return true.');
        }, 'element.hasAttributes() must return true when the element has attribute.');
    }
}
