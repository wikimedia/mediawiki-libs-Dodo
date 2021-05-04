<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementretrieveattrvalue.js.
class HcElementretrieveattrvalueTest extends W3cTestHarness
{
    public function testHcElementretrieveattrvalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
