<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/namednodemap-supported-property-names.html.
class NamednodemapSupportedPropertyNamesTest extends WPTTestHarness
{
    public function testNamednodemapSupportedPropertyNames()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/namednodemap-supported-property-names.html');
        $this->assertTest(function () {
            $elt = $this->doc->querySelector('#simple');
            $this->wptAssertArrayEquals($this->getOwnPropertyNames($elt->attributes), ['0', '1', 'id', 'class']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap');
        $this->assertTest(function () {
            $result = $this->doc->getElementById('result');
            $this->wptAssertArrayEquals($this->getOwnPropertyNames($result->attributes), ['0', '1', '2', '3', 'id', 'type', 'value', 'width']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap of input');
        $this->assertTest(function () {
            $result = $this->doc->getElementById('result');
            $result->removeAttribute('width');
            $this->wptAssertArrayEquals($this->getOwnPropertyNames($result->attributes), ['0', '1', '2', 'id', 'type', 'value']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap after attribute removal');
    }
}
