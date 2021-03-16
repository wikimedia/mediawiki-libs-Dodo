<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedatabegining.js.
class HcCharacterdatareplacedatabeginingTest extends DomTestCase
{
    public function testHcCharacterdatareplacedatabegining()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdatareplacedatabegining') != null) {
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
        $child->replaceData(0, 4, '2500');
        $childData = $child->data;
        $this->assertEqualsData('characterdataReplaceDataBeginingAssert', '2500 North Ave. Dallas, Texas 98551', $childData);
    }
}