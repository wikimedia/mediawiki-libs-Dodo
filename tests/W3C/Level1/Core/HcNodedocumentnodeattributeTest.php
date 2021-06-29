<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodeattribute.js.
class HcNodedocumentnodeattributeTest extends W3CTestHarness
{
    public function testHcNodedocumentnodeattribute()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodeattribute') != null) {
            return;
        }
        $doc = null;
        $attrList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $attrList = $doc->attributes;
        $this->assertNullData('doc_attributes_is_null', $attrList);
    }
}
