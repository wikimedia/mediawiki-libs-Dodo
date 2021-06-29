<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/documentgetdoctypenodtd.js.
class DocumentgetdoctypenodtdTest extends W3CTestHarness
{
    public function testDocumentgetdoctypenodtd()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
