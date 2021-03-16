<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_entitiessetnameditem1.js.
class HcEntitiessetnameditem1Test extends DomTestCase
{
    public function testHcEntitiessetnameditem1()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_entitiessetnameditem1') != null) {
            return;
        }
        $doc = null;
        $entities = null;
        $docType = null;
        $retval = null;
        $elem = null;
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
            $elem = $doc->createElement('br');
            try {
                $retval = $entities->setNamedItem($elem);
                $this->makeFailed('throw_HIER_OR_NO_MOD_ERR');
            } catch (DomException $ex) {
                $this->assertEquals(DOMException::NO_MODIFICATION_ALLOWED_ERR, $ex->getCode());
            }
        }
    }
}