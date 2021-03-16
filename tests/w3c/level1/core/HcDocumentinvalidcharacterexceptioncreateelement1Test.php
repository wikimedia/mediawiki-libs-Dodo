<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentinvalidcharacterexceptioncreateelement1.js.
class HcDocumentinvalidcharacterexceptioncreateelement1Test extends DomTestCase
{
    public function testHcDocumentinvalidcharacterexceptioncreateelement1()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentinvalidcharacterexceptioncreateelement1') != null) {
            return;
        }
        $doc = null;
        $badElement = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $success = false;
        try {
            $badElement = $doc->createElement('');
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
        }
        $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
    }
}