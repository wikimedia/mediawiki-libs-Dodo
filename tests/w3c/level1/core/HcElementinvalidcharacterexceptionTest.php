<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementinvalidcharacterexception.js.
class HcElementinvalidcharacterexceptionTest extends DomTestCase
{
    public function testHcElementinvalidcharacterexception()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementinvalidcharacterexception') != null) {
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
            $testAddress->setAttribute('invalid^Name', 'value');
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
        }
        $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
    }
}