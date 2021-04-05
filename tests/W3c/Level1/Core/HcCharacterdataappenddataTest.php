<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataappenddata.js.
class HcCharacterdataappenddataTest extends W3cTestHarness
{
    public function testHcCharacterdataappenddata()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdataappenddata') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childValue = null;
        $childLength = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->appendData(', Esquire');
        $childValue = $child->data;
        $childLength = count($childValue);
        $this->assertEqualsData('characterdataAppendDataAssert', 24, $childLength);
    }
}
