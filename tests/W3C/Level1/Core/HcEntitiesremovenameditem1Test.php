<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_entitiesremovenameditem1.js.
class HcEntitiesremovenameditem1Test extends W3CTestHarness
{
    public function testHcEntitiesremovenameditem1()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_entitiesremovenameditem1') != null) {
            return;
        }
        $doc = null;
        $entities = null;
        $docType = null;
        $retval = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->w3cAssertNotNull('docTypeNotNull', $docType);
            $entities = $docType->entities;
            $this->w3cAssertNotNull('entitiesNotNull', $entities);
            $success = false;
            try {
                $retval = $entities->removeNamedItem('alpha');
            } catch (DOMException $ex) {
                $success = gettype($ex->code) != NULL && $ex->code == 7;
            }
            $this->w3cAssertTrue('throw_NO_MODIFICATION_ALLOWED_ERR', $success);
        }
    }
}
