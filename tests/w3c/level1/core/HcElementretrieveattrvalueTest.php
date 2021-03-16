<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementretrieveattrvalue.js.
class HcElementretrieveattrvalueTest extends DomTestCase
{
    public function testHcElementretrieveattrvalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementretrieveattrvalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddress = null;
        $attrValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddress = $elementList->item(2);
        $attrValue = $testAddress->getAttribute('class');
        $this->assertEqualsData('attrValue', 'No', $attrValue);
    }
}