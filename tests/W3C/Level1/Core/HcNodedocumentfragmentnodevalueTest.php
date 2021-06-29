<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentfragmentnodevalue.js.
class HcNodedocumentfragmentnodevalueTest extends W3CTestHarness
{
    public function testHcNodedocumentfragmentnodevalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodedocumentfragmentnodevalue') != null) {
            return;
        }
        $doc = null;
        $docFragment = null;
        $attrList = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docFragment = $doc->createDocumentFragment();
        $attrList = $docFragment->attributes;
        $this->assertNullData('attributesNull', $attrList);
        $value = $docFragment->nodeValue;
        $this->assertNullData('initiallyNull', $value);
    }
}
