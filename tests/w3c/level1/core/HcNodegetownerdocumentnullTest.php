<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetownerdocumentnull.js.
class HcNodegetownerdocumentnullTest extends DomTestCase
{
    public function testHcNodegetownerdocumentnull()
    {
        $builder = $this->getBuilder();
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