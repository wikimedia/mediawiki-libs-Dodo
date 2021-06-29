<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodename.js.
class HcNodedocumentnodenameTest extends W3CTestHarness
{
    public function testHcNodedocumentnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
