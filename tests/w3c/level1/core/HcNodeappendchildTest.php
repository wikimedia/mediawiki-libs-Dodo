<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchild.js.
class HcNodeappendchildTest extends DomTestCase
{
    public function testHcNodeappendchild()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsAutoCaseData('element', 'nodeName', 'br', $childName);
    }
}