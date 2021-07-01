<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue08.js.
class HcNodevalue08Test extends W3CTestHarness
{
    public function testHcNodevalue08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodevalue08') != null) {
            return;
        }
        $doc = null;
        $docType = null;
        $newNode = null;
        $newValue = null;
        $nodeMap = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->w3cAssertNotNull('docTypeNotNull', $docType);
            $nodeMap = $docType->notations;
            $this->w3cAssertNotNull('notationsNotNull', $nodeMap);
            $newNode = $nodeMap->getNamedItem('notation1');
            $this->w3cAssertNotNull('notationNotNull', $newNode);
            $newValue = $newNode->nodeValue;
            $this->w3cAssertNull('initiallyNull', $newValue);
            $newNode->nodeValue = 'This should have no effect';
            $newValue = $newNode->nodeValue;
            $this->w3cAssertNull('nullAfterAttemptedChange', $newValue);
        }
    }
}
