<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodeattributes.js.
class HcNodeelementnodeattributesTest extends W3cTestHarness
{
    public function testHcNodeelementnodeattributes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
            $this->assertEqualsCollectionData('attrNames_html', array_map('strtolower', $htmlExpected), array_map('strtolower', $attrList));
        } else {
            $this->assertEqualsCollectionData('attrNames', $expected, $attrList);
        }
    }
}
