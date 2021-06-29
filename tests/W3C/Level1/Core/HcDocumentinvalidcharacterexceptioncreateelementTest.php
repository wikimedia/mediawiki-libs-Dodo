<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentinvalidcharacterexceptioncreateelement.js.
class HcDocumentinvalidcharacterexceptioncreateelementTest extends W3CTestHarness
{
    public function testHcDocumentinvalidcharacterexceptioncreateelement()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentinvalidcharacterexceptioncreateelement') != null) {
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
            $badElement = $doc->createElement('invalid^Name');
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
        }
        $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
    }
}
