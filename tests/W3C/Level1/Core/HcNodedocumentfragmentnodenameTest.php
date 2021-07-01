<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentfragmentnodename.js.
class HcNodedocumentfragmentnodenameTest extends W3CTestHarness
{
    public function testHcNodedocumentfragmentnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodedocumentfragmentnodename') != null) {
            return;
        }
        $doc = null;
        $docFragment = null;
        $documentFragmentName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docFragment = $doc->createDocumentFragment();
        $documentFragmentName = $docFragment->nodeName;
        $this->w3cAssertEquals('nodeDocumentFragmentNodeNameAssert1', '#document-fragment', $documentFragmentName);
    }
}
