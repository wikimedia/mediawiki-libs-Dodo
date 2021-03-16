<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/documentinvalidcharacterexceptioncreatepi1.js.
class Documentinvalidcharacterexceptioncreatepi1Test extends DomTestCase
{
    public function testDocumentinvalidcharacterexceptioncreatepi1()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'documentinvalidcharacterexceptioncreatepi1') != null) {
            return;
        }
        $doc = null;
        $badPI = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        if ($builder->contentType == 'text/html' && false) {
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
                $badPI = $doc->createProcessingInstruction('', 'data');
            } catch (DomException $ex) {
                $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 5;
            }
            $this->assertTrueData('throw_INVALID_CHARACTER_ERR', $success);
        }
    }
}