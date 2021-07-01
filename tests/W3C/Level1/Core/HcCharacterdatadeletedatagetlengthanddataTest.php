<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatadeletedatagetlengthanddata.js.
class HcCharacterdatadeletedatagetlengthanddataTest extends W3CTestHarness
{
    public function testHcCharacterdatadeletedatagetlengthanddata()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatadeletedatagetlengthanddata') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childData = null;
        $childLength = null;
        $result = [];
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $child->deleteData(30, 5);
        $childData = $child->data;
        $this->w3cAssertEquals('data', '1230 North Ave. Dallas, Texas ', $childData);
        $childLength = count($child);
        $this->w3cAssertEquals('length', 30, $childLength);
    }
}
