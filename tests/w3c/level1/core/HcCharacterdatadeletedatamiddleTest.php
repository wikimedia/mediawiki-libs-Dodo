<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatadeletedatamiddle.js.
class HcCharacterdatadeletedatamiddleTest extends DomTestCase
{
    public function testHcCharacterdatadeletedatamiddle()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdatadeletedatamiddle') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childData = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->deleteData(16, 8);
        $childData = $child->data;
        $this->assertEqualsData('characterdataDeleteDataMiddleAssert', '1230 North Ave. Texas 98551', $childData);
    }
}