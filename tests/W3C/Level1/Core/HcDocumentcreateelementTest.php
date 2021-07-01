<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreateelement.js.
class HcDocumentcreateelementTest extends W3CTestHarness
{
    public function testHcDocumentcreateelement()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentcreateelement') != null) {
            return;
        }
        $doc = null;
        $newElement = null;
        $newElementName = null;
        $newElementType = null;
        $newElementValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newElement = $doc->createElement('acronym');
        $newElementName = $newElement->nodeName;
        $this->w3cAssertEqualsAutoCase('element', 'strong', 'acronym', $newElementName);
        $newElementType = $newElement->nodeType;
        $this->w3cAssertEquals('type', 1, $newElementType);
        $newElementValue = $newElement->nodeValue;
        $this->w3cAssertNull('valueInitiallyNull', $newElementValue);
    }
}
