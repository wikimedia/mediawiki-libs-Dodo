<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue06.js.
class HcNodevalue06Test extends W3CTestHarness
{
    public function testHcNodevalue06()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodevalue06') != null) {
            return;
        }
        $newNode = null;
        $newValue = null;
        $newNodeRef = null;
        if (gettype($this->newNode) != NULL) {
            $newNodeRef = $this->newNode;
        }
        $newNode = $this->load($newNodeRef, 'newNode', 'hc_staff');
        $newValue = $newNode->nodeValue;
        $this->w3cAssertNull('initiallyNull', $newValue);
        $newNode->nodeValue = 'This should have no effect';
        $newValue = $newNode->nodeValue;
        $this->w3cAssertNull('nullAfterAttemptedChange', $newValue);
    }
}
