<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/domstringmap-supported-property-names.html.
class DomstringmapSupportedPropertyNamesTest extends WPTTestHarness
{
    public function testDomstringmapSupportedPropertyNames()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/domstringmap-supported-property-names.html');
        $this->assertTest(function () {
            $element = $this->doc->querySelector('#edge1');
            $this->assertArrayEqualsData($this->getOwnPropertyNames($element->dataset), ['']);
        }, 'Object.getOwnPropertyNames on DOMStringMap, empty data attribute');
        $this->assertTest(function () {
            $element = $this->doc->querySelector('#edge2');
            $this->assertArrayEqualsData($this->getOwnPropertyNames($element->dataset), ['id-']);
        }, 'Object.getOwnPropertyNames on DOMStringMap, data attribute trailing hyphen');
        $this->assertTest(function () {
            $element = $this->doc->querySelector('#user');
            $this->assertArrayEqualsData($this->getOwnPropertyNames($element->dataset), ['id', 'user', 'dateOfBirth']);
        }, 'Object.getOwnPropertyNames on DOMStringMap, multiple data attributes');
        $this->assertTest(function () {
            $element = $this->doc->querySelector('#user2');
            $element->dataset->middleName = 'mark';
            $this->assertArrayEqualsData($this->getOwnPropertyNames($element->dataset), ['uniqueId', 'middleName']);
        }, 'Object.getOwnPropertyNames on DOMStringMap, attribute set on dataset in JS');
        $this->assertTest(function () {
            $element = $this->doc->querySelector('#user3');
            $element->setAttribute('data-age', 30);
            $this->assertArrayEqualsData($this->getOwnPropertyNames($element->dataset), ['uniqueId', 'age']);
        }, 'Object.getOwnPropertyNames on DOMStringMap, attribute set on element in JS');
    }
}
