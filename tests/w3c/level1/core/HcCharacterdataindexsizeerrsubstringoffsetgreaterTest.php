<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdataindexsizeerrsubstringoffsetgreater.js.
class HcCharacterdataindexsizeerrsubstringoffsetgreaterTest extends DomTestCase
{
    public function testHcCharacterdataindexsizeerrsubstringoffsetgreater()
    {
        $builder = $this->getBuilder();
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
        $nameNode = $elementList[0];
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