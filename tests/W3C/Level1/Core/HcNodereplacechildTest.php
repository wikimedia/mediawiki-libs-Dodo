<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechild.js.
class HcNodereplacechildTest extends W3CTestHarness
{
    public function testHcNodereplacechild()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodereplacechild') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $oldChild = null;
        $newChild = null;
        $child = null;
        $childName = null;
        $replacedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $oldChild = $childList->item(0);
        $newChild = $doc->createElement('br');
        $replacedNode = $employeeNode->replaceChild($newChild, $oldChild);
        $child = $childList->item(0);
        $childName = $child->nodeName;
        $this->assertEqualsAutoCaseData('element', 'nodeName', 'br', $childName);
    }
}
