<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechild.js.
class HcNoderemovechildTest extends W3CTestHarness
{
    public function testHcNoderemovechild()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_noderemovechild') != null) {
            return;
        }
        $doc = null;
        $rootNode = null;
        $childList = null;
        $childToRemove = null;
        $removedChild = null;
        $parentNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $rootNode = $doc->documentElement;
        $childList = $rootNode->childNodes;
        $childToRemove = $childList->item(1);
        $removedChild = $rootNode->removeChild($childToRemove);
        $parentNode = $removedChild->parentNode;
        $this->w3cAssertNull('parentNodeNull', $parentNode);
    }
}
