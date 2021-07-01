<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentinvalidcharacterexceptioncreateelement1.js.
class HcDocumentinvalidcharacterexceptioncreateelement1Test extends W3CTestHarness
{
    public function testHcDocumentinvalidcharacterexceptioncreateelement1()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 5;
        }
        $this->w3cAssertTrue('throw_INVALID_CHARACTER_ERR', $success);
    }
}
