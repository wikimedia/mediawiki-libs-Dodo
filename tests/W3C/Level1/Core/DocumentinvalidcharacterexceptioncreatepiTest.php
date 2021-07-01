<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/documentinvalidcharacterexceptioncreatepi.js.
class DocumentinvalidcharacterexceptioncreatepiTest extends W3CTestHarness
{
    public function testDocumentinvalidcharacterexceptioncreatepi()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'documentinvalidcharacterexceptioncreatepi') != null) {
            return;
        }
        $doc = null;
        $badPI = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        if ($builder->contentType == 'text/html') {
            $success = false;
            try {
                $badPI = $doc->createProcessingInstruction('foo', 'data');
            } catch (DOMException $ex) {
                $success = gettype($ex->code) != NULL && $ex->code == 9;
            }
            $this->w3cAssertTrue('throw_NOT_SUPPORTED_ERR', $success);
        } else {
            $success = false;
            try {
                $badPI = $doc->createProcessingInstruction('invalid^Name', 'data');
            } catch (DOMException $ex) {
                $success = gettype($ex->code) != NULL && $ex->code == 5;
            }
            $this->w3cAssertTrue('throw_INVALID_CHARACTER_ERR', $success);
        }
    }
}
