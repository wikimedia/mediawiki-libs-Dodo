<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue02.js.
class HcNodevalue02Test extends W3CTestHarness
{
    public function testHcNodevalue02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodevalue02') != null) {
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
        $newNode = $doc->createComment('This is a new Comment node');
        $newValue = $newNode->nodeValue;
        $this->w3cAssertEquals('initial', 'This is a new Comment node', $newValue);
        $newNode->nodeValue = 'This should have an effect';
        $newValue = $newNode->nodeValue;
        $this->w3cAssertEquals('afterChange', 'This should have an effect', $newValue);
    }
}
