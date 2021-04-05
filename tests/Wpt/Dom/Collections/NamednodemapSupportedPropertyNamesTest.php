<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/namednodemap-supported-property-names.html.
class NamednodemapSupportedPropertyNamesTest extends WptTestHarness
{
    public function testNamednodemapSupportedPropertyNames()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/collections/namednodemap-supported-property-names.html';
        $this->assertTest(function () {
            $elt = $this->doc->querySelector('#simple');
            $this->assertArrayEqualsData(get_object_vars($elt->attributes), ['0', '1', 'id', 'class']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap');
        $this->assertTest(function () {
            $result = $this->doc->getElementById('result');
            $this->assertArrayEqualsData(get_object_vars($result->attributes), ['0', '1', '2', '3', 'id', 'type', 'value', 'width']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap of input');
        $this->assertTest(function () {
            $result = $this->doc->getElementById('result');
            $result->removeAttribute('width');
            $this->assertArrayEqualsData(get_object_vars($result->attributes), ['0', '1', '2', 'id', 'type', 'value']);
        }, 'Object.getOwnPropertyNames on NamedNodeMap after attribute removal');
    }
}
