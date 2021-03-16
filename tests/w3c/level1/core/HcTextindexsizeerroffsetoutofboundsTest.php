<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textindexsizeerroffsetoutofbounds.js.
class HcTextindexsizeerroffsetoutofboundsTest extends DomTestCase
{
    public function testHcTextindexsizeerroffsetoutofbounds()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_textindexsizeerroffsetoutofbounds') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $textNode = null;
        $splitNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(2);
        $textNode = $nameNode->firstChild;
        $success = false;
        try {
            $splitNode = $textNode->splitText(300);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 1;
        }
        $this->assertTrueData('throw_INDEX_SIZE_ERR', $success);
    }
}