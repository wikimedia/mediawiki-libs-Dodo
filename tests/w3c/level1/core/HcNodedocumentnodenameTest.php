<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodename.js.
class HcNodedocumentnodenameTest extends DomTestCase
{
    public function testHcNodedocumentnodename()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodename') != null) {
            return;
        }
        $doc = null;
        $documentName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $documentName = $doc->nodeName;
        $this->assertEqualsData('documentNodeName', '#document', $documentName);
    }
}