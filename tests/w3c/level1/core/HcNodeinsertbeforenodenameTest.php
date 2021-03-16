<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforenodename.js.
class HcNodeinsertbeforenodenameTest extends DomTestCase
{
    public function testHcNodeinsertbeforenodename()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforenodename') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $refChild = null;
        $newChild = null;
        $insertedNode = null;
        $childName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $refChild = $childList->item(3);
        $newChild = $doc->createElement('br');
        $insertedNode = $employeeNode->insertBefore($newChild, $refChild);
        $childName = $insertedNode->nodeName;
        $this->assertEqualsAutoCaseData('element', 'nodeName', 'br', $childName);
    }
}