<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementremoveattribute.js.
class HcElementremoveattributeTest extends DomTestCase
{
    public function testHcElementremoveattribute()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementremoveattribute') != null) {
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
        $testEmployee->removeAttribute('class');
        $attrValue = $testEmployee->getAttribute('class');
        $this->assertEqualsData('attrValue', null, $attrValue);
        //XXX Domino returns null as WebKit and FF do
    }
}