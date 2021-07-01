<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementinvalidcharacterexception1.js.
class HcElementinvalidcharacterexception1Test extends W3CTestHarness
{
    public function testHcElementinvalidcharacterexception1()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementinvalidcharacterexception1') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddress = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddress = $elementList->item(0);
        $success = false;
        try {
            $testAddress->setAttribute('', 'value');
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 5;
        }
        $this->w3cAssertTrue('throw_INVALID_CHARACTER_ERR', $success);
    }
}
