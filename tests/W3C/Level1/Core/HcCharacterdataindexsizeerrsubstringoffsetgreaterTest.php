<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrsubstringoffsetgreater.js.
class HcCharacterdataindexsizeerrsubstringoffsetgreaterTest extends W3CTestHarness
{
    public function testHcCharacterdataindexsizeerrsubstringoffsetgreater()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdataindexsizeerrsubstringoffsetgreater') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $badString = null;
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
            $badString = $child->substringData(40, 3);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 1;
        }
        $this->assertTrueData('throw_INDEX_SIZE_ERR', $success);
    }
}