<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentfragmentnodename.js.
class HcNodedocumentfragmentnodenameTest extends DomTestCase
{
    public function testHcNodedocumentfragmentnodename()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('nodeDocumentFragmentNodeNameAssert1', '#document-fragment', $documentFragmentName);
    }
}