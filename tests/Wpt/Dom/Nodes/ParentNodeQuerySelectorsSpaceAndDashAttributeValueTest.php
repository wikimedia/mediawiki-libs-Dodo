<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-space-and-dash-attribute-value.html.
class ParentNodeQuerySelectorsSpaceAndDashAttributeValueTest extends WptTestHarness
{
    public function testParentNodeQuerySelectorsSpaceAndDashAttributeValue()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-space-and-dash-attribute-value.html');
        $el = $this->doc->getElementById('testme');
        $this->assertTest(function () use(&$el) {
            $this->assertEqualsData($this->doc->querySelector("a[title='test with - dash and space']"), $el);
        }, 'querySelector');
        $this->assertTest(function () use(&$el) {
            $this->assertEqualsData($this->doc->querySelector("a[title='test with - dash and space']"), $el);
        }, 'querySelectorAll');
    }
}
