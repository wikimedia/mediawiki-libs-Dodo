<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodetype.js.
class HcNodedocumentnodetypeTest extends DomTestCase
{
    public function testHcNodedocumentnodetype()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodetype') != null) {
            return;
        }
        $doc = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $nodeType = $doc->nodeType;
        $this->assertEqualsData('nodeDocumentNodeTypeAssert1', 9, $nodeType);
    }
}