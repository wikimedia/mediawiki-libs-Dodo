<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-space-and-dash-attribute-value.html.
class ParentNodeQuerySelectorsSpaceAndDashAttributeValueTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorsSpaceAndDashAttributeValue()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-space-and-dash-attribute-value.html');
        $el = $this->doc->getElementById('testme');
        $this->assertTest(function () use(&$el) {
            $this->assertEqualsData($this->doc->querySelector("a[title='test with - dash and space']"), $el);
        }, 'querySelector');
        $this->assertTest(function () use(&$el) {
            $this->assertEqualsData($this->doc->querySelector("a[title='test with - dash and space']"), $el);
        }, 'querySelectorAll');
    }
}
