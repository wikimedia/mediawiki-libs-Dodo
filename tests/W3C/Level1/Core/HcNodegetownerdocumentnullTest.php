<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetownerdocumentnull.js.
class HcNodegetownerdocumentnullTest extends W3CTestHarness
{
    public function testHcNodegetownerdocumentnull()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodegetownerdocumentnull') != null) {
            return;
        }
        $doc = null;
        $ownerDocument = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $ownerDocument = $doc->ownerDocument;
        $this->assertNullData('nodeGetOwnerDocumentNullAssert1', $ownerDocument);
    }
}
