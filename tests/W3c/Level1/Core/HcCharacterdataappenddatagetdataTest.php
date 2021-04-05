<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataappenddatagetdata.js.
class HcCharacterdataappenddatagetdataTest extends W3cTestHarness
{
    public function testHcCharacterdataappenddatagetdata()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdataappenddatagetdata') != null) {
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
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->appendData(', Esquire');
        $childData = $child->data;
        $this->assertEqualsData('characterdataAppendDataGetDataAssert', 'Margaret Martin, Esquire', $childData);
    }
}
