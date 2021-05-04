<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue04.js.
class HcNodevalue04Test extends W3cTestHarness
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
        $this->assertTrueData('docTypeNotNullOrDocIsHTML', $newNode != null || $builder->contentType == 'text/html');
        if ($newNode != null) {
            $this->assertNotNullData('docTypeNotNull', $newNode);
            $newValue = $newNode->nodeValue;
            $this->assertNullData('initiallyNull', $newValue);
            $newNode->nodeValue = 'This should have no effect';
            $newValue = $newNode->nodeValue;
            $this->assertNullData('nullAfterAttemptedChange', $newValue);
        }
    }
}
