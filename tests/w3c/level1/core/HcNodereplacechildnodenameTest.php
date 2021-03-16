<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildnodename.js.
class HcNodereplacechildnodenameTest extends DomTestCase
{
    public function testHcNodereplacechildnodename()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodereplacechildnodename') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $oldChild = null;
        $newChild = null;
        $replacedNode = null;
        $childName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->getElementsByTagName('em');
        $oldChild = $childList[0];
        $newChild = $doc->createElement('br');
        $replacedNode = $employeeNode->replaceChild($newChild, $oldChild);
        $childName = $replacedNode->nodeName;
        $this->assertEqualsAutoCaseData('element', 'replacedNodeName', 'em', $childName);
    }
}