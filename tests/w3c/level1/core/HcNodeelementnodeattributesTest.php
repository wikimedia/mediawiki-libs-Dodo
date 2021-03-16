<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodeattributes.js.
class HcNodeelementnodeattributesTest extends DomTestCase
{
    public function testHcNodeelementnodeattributes()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeelementnodeattributes') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddr = null;
        $addrAttr = null;
        $attrNode = null;
        $attrName = null;
        $attrList = [];
        $htmlExpected = [];
        $htmlExpected[0] = 'title';
        $htmlExpected[1] = 'class';
        $expected = [];
        $expected[0] = 'title';
        $expected[1] = 'class';
        $expected[2] = 'dir';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddr = $elementList->item(2);
        $addrAttr = $testAddr->attributes;
        for ($indexN10070 = 0; $indexN10070 < count($addrAttr); $indexN10070++) {
            $attrNode = $addrAttr->item($indexN10070);
            $attrName = $attrNode->name;
            $attrList[count($attrList)] = $attrName;
        }
        if ($builder->contentType == 'text/html') {
            $this->assertEqualsCollectionData('attrNames_html', toLowerArray($htmlExpected), toLowerArray($attrList));
        } else {
            $this->assertEqualsCollectionData('attrNames', $expected, $attrList);
        }
    }
}