<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrreplacedataoffsetgreater.js.
class HcCharacterdataindexsizeerrreplacedataoffsetgreaterTest extends W3CTestHarness
{
    public function testHcCharacterdataindexsizeerrreplacedataoffsetgreater()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdataindexsizeerrreplacedataoffsetgreater') != null) {
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
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $success = false;
        try {
            $child->deleteData(40, 3);
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 1;
        }
        $this->w3cAssertTrue('throw_INDEX_SIZE_ERR', $success);
    }
}
