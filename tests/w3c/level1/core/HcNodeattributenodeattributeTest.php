<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeattributenodeattribute.js.
class HcNodeattributenodeattributeTest extends DomTestCase
{
    public function testHcNodeattributenodeattribute()
    {
        $builder = $this->getBuilder();
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
        $testAddr = $elementList[0];
        $addrAttr = $testAddr->attributes;
        $attrNode = $addrAttr[0];
        $attrList = $attrNode->attributes;
        $this->assertNullData('nodeAttributeNodeAttributeAssert1', $attrList);
    }
}