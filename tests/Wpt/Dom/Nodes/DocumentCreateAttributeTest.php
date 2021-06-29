<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createAttribute.html.
class DocumentCreateAttributeTest extends WptTestHarness
{
    public function testDocumentCreateAttribute()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createAttribute.html');
        $xml_document = null;
        // setup()
        $xml_document = $this->doc->implementation->createDocument(null, null, null);
        foreach ($this->invalid_names as $name) {
            $this->assertTest(function () use(&$name) {
                $this->assertThrowsDomData('INVALID_CHARACTER_ERR', function () use(&$name) {
                    $this->doc->createAttribute($name, 'test');
                });
            }, 'HTML document.createAttribute(' . $this->formatValue($name) . ') should throw');
            $this->assertTest(function () use(&$xml_document, &$name) {
                $this->assertThrowsDomData('INVALID_CHARACTER_ERR', function () use(&$xml_document, &$name) {
                    $xml_document->createAttribute($name, 'test');
                });
            }, 'XML document.createAttribute(' . $this->formatValue($name) . ') should throw');
        }
        foreach ($this->valid_names as $name) {
            $this->assertTest(function () use(&$name) {
                $attr = $this->doc->createAttribute($name);
                $this->attrIs($attr, '', strtolower($name), null, null, strtolower($name));
            }, "HTML document.createAttribute({format_value( {$name} )})");
            $this->assertTest(function () use(&$xml_document, &$name) {
                $attr = $xml_document->createAttribute($name);
                $this->attrIs($attr, '', $name, null, null, $name);
            }, "XML document.createAttribute({format_value( {$name} )})");
        }
        $tests = ['title', 'TITLE', null, null];
        foreach ($tests as $name) {
            $this->assertTest(function () use(&$name) {
                $attribute = $this->doc->createAttribute($name);
                $this->attrIs($attribute, '', strtolower(strval($name)), null, null, strtolower(strval($name)));
                $this->assertEqualsData($attribute->ownerElement, null);
            }, 'HTML document.createAttribute(' . $this->formatValue($name) . ')');
            $this->assertTest(function () use(&$xml_document, &$name) {
                $attribute = $xml_document->createAttribute($name);
                $this->attrIs($attribute, '', strval($name), null, null, strval($name));
                $this->assertEqualsData($attribute->ownerElement, null);
            }, 'XML document.createAttribute(' . $this->formatValue($name) . ')');
        }
    }
}
