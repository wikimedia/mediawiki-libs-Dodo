<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildnodename.js.
class HcNodereplacechildnodenameTest extends W3CTestHarness
{
    public function testHcNodereplacechildnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $oldChild = $childList->item(0);
        $newChild = $doc->createElement('br');
        $replacedNode = $employeeNode->replaceChild($newChild, $oldChild);
        $childName = $replacedNode->nodeName;
        $this->w3cAssertEqualsAutoCase('element', 'replacedNodeName', 'em', $childName);
    }
}
