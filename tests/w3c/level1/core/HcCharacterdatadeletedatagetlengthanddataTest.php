<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatadeletedatagetlengthanddata.js.
class HcCharacterdatadeletedatagetlengthanddataTest extends DomTestCase
{
    public function testHcCharacterdatadeletedatagetlengthanddata()
    {
        $builder = $this->getBuilder();
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
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->deleteData(30, 5);
        $childData = $child->data;
        $this->assertEqualsData('data', '1230 North Ave. Dallas, Texas ', $childData);
        $childLength = count($child);
        $this->assertEqualsData('length', 30, $childLength);
    }
}