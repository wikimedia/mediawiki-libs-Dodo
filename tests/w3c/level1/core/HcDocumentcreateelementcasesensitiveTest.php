<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreateelementcasesensitive.js.
class HcDocumentcreateelementcasesensitiveTest extends DomTestCase
{
    public function testHcDocumentcreateelementcasesensitive()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentcreateelementcasesensitive') != null) {
            return;
        }
        $doc = null;
        $newElement1 = null;
        $newElement2 = null;
        $attribute1 = null;
        $attribute2 = null;
        $nodeName1 = null;
        $nodeName2 = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newElement1 = $doc->createElement('ACRONYM');
        $newElement2 = $doc->createElement('acronym');
        $newElement1->setAttribute('lang', 'EN');
        $newElement2->setAttribute('title', 'Dallas');
        $attribute1 = $newElement1->getAttribute('lang');
        $attribute2 = $newElement2->getAttribute('title');
        $this->assertEqualsData('attrib1', 'EN', $attribute1);
        $this->assertEqualsData('attrib2', 'Dallas', $attribute2);
        $nodeName1 = $newElement1->nodeName;
        $nodeName2 = $newElement2->nodeName;
        $this->assertEqualsAutoCaseData('element', 'nodeName1', 'ACRONYM', $nodeName1);
        $this->assertEqualsAutoCaseData('element', 'nodeName2', 'acronym', $nodeName2);
    }
}