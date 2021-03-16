<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentfragmentnodetype.js.
class HcNodedocumentfragmentnodetypeTest extends DomTestCase
{
    public function testHcNodedocumentfragmentnodetype()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentfragmentnodetype') != null) {
            return;
        }
        $doc = null;
        $documentFragmentNode = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $documentFragmentNode = $doc->createDocumentFragment();
        $nodeType = $documentFragmentNode->nodeType;
        $this->assertEqualsData('nodeDocumentFragmentNodeTypeAssert1', 11, $nodeType);
    }
}