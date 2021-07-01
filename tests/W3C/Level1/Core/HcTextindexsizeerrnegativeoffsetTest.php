<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textindexsizeerrnegativeoffset.js.
class HcTextindexsizeerrnegativeoffsetTest extends W3CTestHarness
{
    public function testHcTextindexsizeerrnegativeoffset()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_textindexsizeerrnegativeoffset') != null) {
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
            $splitNode = $textNode->splitText(-69);
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 1;
        }
        $this->w3cAssertTrue('throws_INDEX_SIZE_ERR', $success);
    }
}
