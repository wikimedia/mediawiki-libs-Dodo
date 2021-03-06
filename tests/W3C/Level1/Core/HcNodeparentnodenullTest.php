<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeparentnodenull.js.
class HcNodeparentnodenullTest extends W3CTestHarness
{
    public function testHcNodeparentnodenull()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeparentnodenull') != null) {
            return;
        }
        $doc = null;
        $createdNode = null;
        $parentNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $createdNode = $doc->createElement('br');
        $parentNode = $createdNode->parentNode;
        $this->w3cAssertNull('parentNode', $parentNode);
    }
}
