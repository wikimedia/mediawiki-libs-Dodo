<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrdeletedataoffsetgreater.js.
class HcCharacterdataindexsizeerrdeletedataoffsetgreaterTest extends W3cTestHarness
{
    public function testHcCharacterdataindexsizeerrdeletedataoffsetgreater()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdataindexsizeerrdeletedataoffsetgreater') != null) {
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
            $child->deleteData(40, 3);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 1;
        }
        $this->assertTrueData('throw_INDEX_SIZE_ERR', $success);
    }
}
