<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_entitiesremovenameditem1.js.
class HcEntitiesremovenameditem1Test extends DomTestCase
{
    public function testHcEntitiesremovenameditem1()
    {
        $builder = $this->getBuilder();
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