<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatainsertdatamiddle.js.
class HcCharacterdatainsertdatamiddleTest extends W3CTestHarness
{
    public function testHcCharacterdatainsertdatamiddle()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatainsertdatamiddle') != null) {
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
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $child->insertData(9, 'Ann ');
        $childData = $child->data;
        $this->w3cAssertEquals('characterdataInsertDataMiddleAssert', 'Margaret Ann Martin', $childData);
    }
}
