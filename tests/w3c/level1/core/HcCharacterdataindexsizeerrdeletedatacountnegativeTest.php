<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrdeletedatacountnegative.js.
class HcCharacterdataindexsizeerrdeletedatacountnegativeTest extends DomTestCase
{
    public function testHcCharacterdataindexsizeerrdeletedatacountnegative()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdataindexsizeerrdeletedatacountnegative') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childSubstring = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $success = false;
        try {
            $childSubstring = $child->substringData(10, -3);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 1;
        }
        $this->assertTrueData('throws_INDEX_SIZE_ERR', $success);
    }
}