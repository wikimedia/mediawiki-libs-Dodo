<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementchangeattributevalue.js.
class HcElementchangeattributevalueTest extends DomTestCase
{
    public function testHcElementchangeattributevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementchangeattributevalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testEmployee = null;
        $attrValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testEmployee = $elementList->item(3);
        $testEmployee->setAttribute('class', 'Neither');
        $attrValue = $testEmployee->getAttribute('class');
        $this->assertEqualsData('elementChangeAttributeValueAssert', 'Neither', $attrValue);
    }
}