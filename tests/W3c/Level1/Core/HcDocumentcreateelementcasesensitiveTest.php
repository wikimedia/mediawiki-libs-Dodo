<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreateelementcasesensitive.js.
class HcDocumentcreateelementcasesensitiveTest extends W3cTestHarness
{
    public function testHcDocumentcreateelementcasesensitive()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
