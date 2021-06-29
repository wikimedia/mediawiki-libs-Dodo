<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildgetnodename.js.
class HcNoderemovechildgetnodenameTest extends W3CTestHarness
{
    public function testHcNoderemovechildgetnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $oldChild = $childList->item(0);
        $oldName = $oldChild->nodeName;
        $removedChild = $employeeNode->removeChild($oldChild);
        $this->assertNotNullData('notnull', $removedChild);
        $childName = $removedChild->nodeName;
        $this->assertEqualsData('nodeName', $oldName, $childName);
    }
}
