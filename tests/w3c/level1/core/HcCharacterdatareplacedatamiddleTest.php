<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedatamiddle.js.
class HcCharacterdatareplacedatamiddleTest extends DomTestCase
{
    public function testHcCharacterdatareplacedatamiddle()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdatareplacedatamiddle') != null) {
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
        $child->replaceData(5, 5, 'South');
        $childData = $child->data;
        $this->assertEqualsData('characterdataReplaceDataMiddleAssert', '1230 South Ave. Dallas, Texas 98551', $childData);
    }
}