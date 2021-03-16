<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforerefchildnull.js.
class HcNodeinsertbeforerefchildnullTest extends DomTestCase
{
    public function testHcNodeinsertbeforerefchildnull()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforerefchildnull') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $refChild = null;
        $newChild = null;
        $child = null;
        $childName = null;
        $insertedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $newChild = $doc->createElement('br');
        $insertedNode = $employeeNode->insertBefore($newChild, $refChild);
        $child = $employeeNode->lastChild;
        $childName = $child->nodeName;
        $this->assertEqualsAutoCaseData('element', 'nodeName', 'br', $childName);
    }
}