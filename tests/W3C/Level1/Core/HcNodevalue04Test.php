<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue04.js.
class HcNodevalue04Test extends W3CTestHarness
{
    public function testHcNodevalue04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodevalue04') != null) {
            return;
        }
        $doc = null;
        $newNode = null;
        $newValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newNode = $doc->doctype;
        $this->w3cAssertTrue('docTypeNotNullOrDocIsHTML', $newNode != null || $builder->contentType == 'text/html');
        if ($newNode != null) {
            $this->w3cAssertNotNull('docTypeNotNull', $newNode);
            $newValue = $newNode->nodeValue;
            $this->w3cAssertNull('initiallyNull', $newValue);
            $newNode->nodeValue = 'This should have no effect';
            $newValue = $newNode->nodeValue;
            $this->w3cAssertNull('nullAfterAttemptedChange', $newValue);
        }
    }
}
