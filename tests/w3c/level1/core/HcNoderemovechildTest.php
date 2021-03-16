<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechild.js.
class HcNoderemovechildTest extends DomTestCase
{
    public function testHcNoderemovechild()
    {
        $builder = $this->getBuilder();
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
        $this->assertNullData('parentNodeNull', $parentNode);
    }
}