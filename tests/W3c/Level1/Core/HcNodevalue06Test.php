<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue06.js.
class HcNodevalue06Test extends W3cTestHarness
{
    public function testHcNodevalue06()
    {
        $builder = $this->getBuilder();
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
        $this->assertNullData('initiallyNull', $newValue);
        $newNode->nodeValue = 'This should have no effect';
        $newValue = $newNode->nodeValue;
        $this->assertNullData('nullAfterAttemptedChange', $newValue);
    }
}
