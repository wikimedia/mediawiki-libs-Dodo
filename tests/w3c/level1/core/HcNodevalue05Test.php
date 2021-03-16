<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue05.js.
class HcNodevalue05Test extends DomTestCase
{
    public function testHcNodevalue05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodevalue05') != null) {
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
        $newNode = $doc->createDocumentFragment();
        $newValue = $newNode->nodeValue;
        $this->assertNullData('initiallyNull', $newValue);
        $newNode->nodeValue = 'This should have no effect';
        $newValue = $newNode->nodeValue;
        $this->assertNullData('nullAfterAttemptedChange', $newValue);
    }
}