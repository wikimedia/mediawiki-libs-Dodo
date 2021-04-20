<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementaddnewattribute.js.
class HcElementaddnewattributeTest extends DomTestCase
{
    public function testHcElementaddnewattribute()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementaddnewattribute') != null) {
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
        $testEmployee = $elementList->item(4);
        $testEmployee->setAttribute('lang', 'EN-us');
        $attrValue = $testEmployee->getAttribute('lang');
        $this->assertEqualsData('attrValue', 'EN-us', $attrValue);
    }
}