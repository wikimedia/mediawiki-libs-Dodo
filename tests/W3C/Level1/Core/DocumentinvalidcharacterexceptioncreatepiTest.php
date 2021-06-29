<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
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
            } catch (DomException $ex) {
                $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 9;
            }
            $this->assertTrueData('throw_NOT_SUPPORTED_ERR', $success);
        } else {
            $success = false;
            try {
                $badPI = $doc->createProcessingInstruction('invalid^Name', 'data');
            } catch (DomException $ex) {
                $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
            }
            $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
        }
    }
}
