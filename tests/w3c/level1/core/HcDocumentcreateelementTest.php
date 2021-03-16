<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreateelement.js.
class HcDocumentcreateelementTest extends DomTestCase
{
    public function testHcDocumentcreateelement()
    {
        $builder = $this->getBuilder();
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