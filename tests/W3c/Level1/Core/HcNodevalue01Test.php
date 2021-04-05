<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue01.js.
class HcNodevalue01Test extends W3cTestHarness
{
    public function testHcNodevalue01()
    {
        $builder = $this->getBuilder();
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
        $this->assertNullData('initiallyNull', $newValue);
        $newNode->nodeValue = 'This should have no effect';
        $newValue = $newNode->nodeValue;
        $this->assertNullData('nullAfterAttemptedChange', $newValue);
    }
}
