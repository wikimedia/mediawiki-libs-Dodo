<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementretrieveallattributes.js.
class HcElementretrieveallattributesTest extends W3cTestHarness
{
    public function testHcElementretrieveallattributes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementretrieveallattributes') != null) {
            return;
        }
        $doc = null;
        $addressList = null;
        $testAddress = null;
        $attributes = null;
        $attribute = null;
        $attributeName = null;
        $actual = [];
        $htmlExpected = [];
        $htmlExpected[0] = 'title';
        $expected = [];
        $expected[0] = 'title';
        $expected[1] = 'dir';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $addressList = $doc->getElementsByTagName('acronym');
        $testAddress = $addressList->item(0);
        $attributes = $testAddress->attributes;
        for ($indexN1006B = 0; $indexN1006B < count($attributes); $indexN1006B++) {
            $attribute = $attributes->item($indexN1006B);
            $attributeName = $attribute->name;
            $actual[count($actual)] = $attributeName;
        }
        if ($builder->contentType == 'text/html') {
            $this->assertEqualsCollectionData('htmlAttributeNames', toLowerArray($htmlExpected), toLowerArray($actual));
        } else {
            $this->assertEqualsCollectionData('attributeNames', toLowerArray($expected), toLowerArray($actual));
        }
    }
}
