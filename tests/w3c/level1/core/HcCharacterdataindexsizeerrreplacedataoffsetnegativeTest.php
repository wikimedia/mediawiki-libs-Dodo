<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrreplacedataoffsetnegative.js.
class HcCharacterdataindexsizeerrreplacedataoffsetnegativeTest extends DomTestCase
{
    public function testHcCharacterdataindexsizeerrreplacedataoffsetnegative()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdataindexsizeerrreplacedataoffsetnegative') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
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
            $child->replaceData(-5, 3, 'ABC');
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 1;
        }
        $this->assertTrueData('throws_INDEX_SIZE_ERR', $success);
    }
}