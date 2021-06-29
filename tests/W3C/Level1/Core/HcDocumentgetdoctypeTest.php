<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetdoctype.js.
class HcDocumentgetdoctypeTest extends W3CTestHarness
{
    public function testHcDocumentgetdoctype()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentgetdoctype') != null) {
            return;
        }
        $doc = null;
        $docType = null;
        $docTypeName = null;
        $nodeValue = null;
        $attributes = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->assertNotNullData('docTypeNotNull', $docType);
        }
        if ($docType != null) {
            $docTypeName = $docType->name;
            if ($builder->contentType == 'image/svg+xml') {
                $this->assertEqualsData('nodeNameSVG', 'svg', $docTypeName);
            } else {
                $this->assertEqualsData('nodeName', 'html', $docTypeName);
            }
            $nodeValue = $docType->nodeValue;
            $this->assertNullData('nodeValue', $nodeValue);
            $attributes = $docType->attributes;
            $this->assertNullData('attributes', $attributes);
        }
    }
}
