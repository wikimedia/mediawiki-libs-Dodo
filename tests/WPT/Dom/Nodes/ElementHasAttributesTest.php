<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttributes.html.
class ElementHasAttributesTest extends WPTTestHarness
{
    public function testElementHasAttributes()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-hasAttributes.html');
        $this->assertTest(function () {
            $buttonElement = $this->doc->getElementsByTagName('button')[0];
            $this->wptAssertEquals($buttonElement->hasAttributes(), false, 'hasAttributes() on empty element must return false.');
            $emptyDiv = $this->doc->createElement('div');
            $this->wptAssertEquals($emptyDiv->hasAttributes(), false, 'hasAttributes() on dynamically created empty element must return false.');
        }, 'element.hasAttributes() must return false when the element does not have attribute.');
        $this->assertTest(function () {
            $divWithId = $this->doc->getElementById('foo');
            $this->wptAssertEquals($divWithId->hasAttributes(), true, 'hasAttributes() on element with id attribute must return true.');
            $divWithClass = $this->doc->createElement('div');
            $divWithClass->setAttribute('class', 'foo');
            $this->wptAssertEquals($divWithClass->hasAttributes(), true, 'hasAttributes() on dynamically created element with class attribute must return true.');
            $pWithCustomAttr = $this->doc->getElementsByTagName('p')[0];
            $this->wptAssertEquals($pWithCustomAttr->hasAttributes(), true, 'hasAttributes() on element with custom attribute must return true.');
            $divWithCustomAttr = $this->doc->createElement('div');
            $divWithCustomAttr->setAttribute('data-custom', 'foo');
            $this->wptAssertEquals($divWithCustomAttr->hasAttributes(), true, 'hasAttributes() on dynamically created element with custom attribute must return true.');
        }, 'element.hasAttributes() must return true when the element has attribute.');
    }
}
