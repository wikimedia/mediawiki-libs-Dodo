<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchild.js.
class HcNodeappendchildTest extends W3CTestHarness
{
    public function testHcNodeappendchild()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeappendchild') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $createdNode = null;
        $lchild = null;
        $childName = null;
        $appendedChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $createdNode = $doc->createElement('br');
        $appendedChild = $employeeNode->appendChild($createdNode);
        $lchild = $employeeNode->lastChild;
        $childName = $lchild->nodeName;
        $this->w3cAssertEqualsAutoCase('element', 'nodeName', 'br', $childName);
    }
}
