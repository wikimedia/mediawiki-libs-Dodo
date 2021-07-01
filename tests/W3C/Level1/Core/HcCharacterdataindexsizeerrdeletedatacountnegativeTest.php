<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrdeletedatacountnegative.js.
class HcCharacterdataindexsizeerrdeletedatacountnegativeTest extends W3CTestHarness
{
    public function testHcCharacterdataindexsizeerrdeletedatacountnegative()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $success = false;
        try {
            $childSubstring = $child->substringData(10, -3);
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 1;
        }
        $this->w3cAssertTrue('throws_INDEX_SIZE_ERR', $success);
    }
}
