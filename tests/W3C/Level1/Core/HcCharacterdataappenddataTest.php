<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataappenddata.js.
class HcCharacterdataappenddataTest extends W3CTestHarness
{
    public function testHcCharacterdataappenddata()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $child->appendData(', Esquire');
        $childValue = $child->data;
        $childLength = strlen($childValue);
        $this->w3cAssertEquals('characterdataAppendDataAssert', 24, $childLength);
    }
}
