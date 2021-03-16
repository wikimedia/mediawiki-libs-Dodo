<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatedocumentfragment.js.
class HcDocumentcreatedocumentfragmentTest extends DomTestCase
{
    public function testHcDocumentcreatedocumentfragment()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('length', 0, $length);
        $newDocFragmentName = $newDocFragment->nodeName;
        $this->assertEqualsData('strong', '#document-fragment', $newDocFragmentName);
        $newDocFragmentType = $newDocFragment->nodeType;
        $this->assertEqualsData('type', 11, $newDocFragmentType);
        $newDocFragmentValue = $newDocFragment->nodeValue;
        $this->assertNullData('value', $newDocFragmentValue);
    }
}