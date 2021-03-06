<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodevalue.js.
class HcNodedocumentnodevalueTest extends W3CTestHarness
{
    public function testHcNodedocumentnodevalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodevalue') != null) {
            return;
        }
        $doc = null;
        $documentValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $documentValue = $doc->nodeValue;
        $this->w3cAssertNull('documentNodeValue', $documentValue);
    }
}
