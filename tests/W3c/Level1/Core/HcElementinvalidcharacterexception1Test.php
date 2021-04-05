<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementinvalidcharacterexception1.js.
class HcElementinvalidcharacterexception1Test extends W3cTestHarness
{
    public function testHcElementinvalidcharacterexception1()
    {
        $builder = $this->getBuilder();
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
        $testAddress = $elementList[0];
        $success = false;
        try {
            $testAddress->setAttribute('', 'value');
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
        }
        $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
    }
}
