<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeattributenodeattribute.js.
class HcNodeattributenodeattributeTest extends W3cTestHarness
{
    public function testHcNodeattributenodeattribute()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeattributenodeattribute') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddr = null;
        $addrAttr = null;
        $attrNode = null;
        $attrList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddr = $elementList->item(0);
        $addrAttr = $testAddr->attributes;
        $attrNode = $addrAttr->item(0);
        $attrList = $attrNode->attributes;
        $this->assertNullData('nodeAttributeNodeAttributeAssert1', $attrList);
    }
}
