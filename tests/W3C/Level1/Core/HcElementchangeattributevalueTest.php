<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementchangeattributevalue.js.
class HcElementchangeattributevalueTest extends W3CTestHarness
{
    public function testHcElementchangeattributevalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertEquals('elementChangeAttributeValueAssert', 'Neither', $attrValue);
    }
}
