<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreateelement.js.
class HcDocumentcreateelementTest extends W3cTestHarness
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
        $this->assertEqualsAutoCaseData('element', 'strong', 'acronym', $newElementName);
        $newElementType = $newElement->nodeType;
        $this->assertEqualsData('type', 1, $newElementType);
        $newElementValue = $newElement->nodeValue;
        $this->assertNullData('valueInitiallyNull', $newElementValue);
    }
}
