<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildgetnodename.js.
class HcNoderemovechildgetnodenameTest extends W3cTestHarness
{
    public function testHcNoderemovechildgetnodename()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_noderemovechildgetnodename') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $oldChild = null;
        $removedChild = null;
        $childName = null;
        $oldName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $oldChild = $childList[0];
        $oldName = $oldChild->nodeName;
        $removedChild = $employeeNode->removeChild($oldChild);
        $this->assertNotNullData('notnull', $removedChild);
        $childName = $removedChild->nodeName;
        $this->assertEqualsData('nodeName', $oldName, $childName);
    }
}
