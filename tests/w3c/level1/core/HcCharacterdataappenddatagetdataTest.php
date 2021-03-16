<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataappenddatagetdata.js.
class HcCharacterdataappenddatagetdataTest extends DomTestCase
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