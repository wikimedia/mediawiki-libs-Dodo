<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue01.js.
class HcNodevalue01Test extends W3CTestHarness
{
    public function testHcNodevalue01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodevalue01') != null) {
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
        $newNode = $doc->createElement('acronym');
        $newValue = $newNode->nodeValue;
        $this->w3cAssertNull('initiallyNull', $newValue);
        $newNode->nodeValue = 'This should have no effect';
        $newValue = $newNode->nodeValue;
        $this->w3cAssertNull('nullAfterAttemptedChange', $newValue);
    }
}
