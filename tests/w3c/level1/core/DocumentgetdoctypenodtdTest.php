<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/documentgetdoctypenodtd.js.
class DocumentgetdoctypenodtdTest extends DomTestCase
{
    public function testDocumentgetdoctypenodtd()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'documentgetdoctypenodtd') != null) {
            return;
        }
        $doc = null;
        $docType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_nodtdstaff');
        $docType = $doc->doctype;
        $this->assertNullData('documentGetDocTypeNoDTDAssert', $docType);
    }
}