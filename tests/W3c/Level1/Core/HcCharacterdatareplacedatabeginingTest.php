<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedatabegining.js.
class HcCharacterdatareplacedatabeginingTest extends W3cTestHarness
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
