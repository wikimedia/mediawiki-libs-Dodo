<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatedocumentfragment.js.
class HcDocumentcreatedocumentfragmentTest extends W3CTestHarness
{
    public function testHcDocumentcreatedocumentfragment()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentcreatedocumentfragment') != null) {
            return;
        }
        $doc = null;
        $newDocFragment = null;
        $children = null;
        $length = null;
        $newDocFragmentName = null;
        $newDocFragmentType = null;
        $newDocFragmentValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newDocFragment = $doc->createDocumentFragment();
        $children = $newDocFragment->childNodes;
        $length = count($children);
        $this->w3cAssertEquals('length', 0, $length);
        $newDocFragmentName = $newDocFragment->nodeName;
        $this->w3cAssertEquals('strong', '#document-fragment', $newDocFragmentName);
        $newDocFragmentType = $newDocFragment->nodeType;
        $this->w3cAssertEquals('type', 11, $newDocFragmentType);
        $newDocFragmentValue = $newDocFragment->nodeValue;
        $this->w3cAssertNull('value', $newDocFragmentValue);
    }
}
