<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecloneattributescopied.js.
class HcNodecloneattributescopiedTest extends W3CTestHarness
{
    public function testHcNodecloneattributescopied()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodecloneattributescopied') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $addressNode = null;
        $clonedNode = null;
        $attributes = null;
        $attributeNode = null;
        $attributeName = null;
        $result = [];
        $htmlExpected = [];
        $htmlExpected[0] = 'class';
        $htmlExpected[1] = 'title';
        $expected = [];
        $expected[0] = 'class';
        $expected[1] = 'title';
        $expected[2] = 'dir';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $addressNode = $elementList->item(1);
        $clonedNode = $addressNode->cloneNode(false);
        $attributes = $clonedNode->attributes;
        for ($indexN10076 = 0; $indexN10076 < count($attributes); $indexN10076++) {
            $attributeNode = $attributes->item($indexN10076);
            $attributeName = $attributeNode->name;
            $result[count($result)] = $attributeName;
        }
        if ($builder->contentType == 'text/html') {
            $this->w3cAssertEqualsCollection('nodeNames_html', array_map('strtolower', $htmlExpected), array_map('strtolower', $result));
        } else {
            $this->w3cAssertEqualsCollection('nodeNames', $expected, $result);
        }
    }
}
