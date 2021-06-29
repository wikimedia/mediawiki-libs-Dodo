<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
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
            $this->assertNotNullData('docTypeNotNull', $docType);
            $entities = $docType->entities;
            $this->assertNotNullData('entitiesNotNull', $entities);
            $success = false;
            try {
                $retval = $entities->removeNamedItem('alpha');
            } catch (DomException $ex) {
                $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 7;
            }
            $this->assertTrueData('throw_NO_MODIFICATION_ALLOWED_ERR', $success);
        }
    }
}
