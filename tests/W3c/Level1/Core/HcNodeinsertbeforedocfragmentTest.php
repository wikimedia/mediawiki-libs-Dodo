<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforedocfragment.js.
class HcNodeinsertbeforedocfragmentTest extends W3cTestHarness
{
    public function testHcNodeinsertbeforedocfragment()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforedocfragment') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $refChild = null;
        $newdocFragment = null;
        $newChild1 = null;
        $newChild2 = null;
        $child = null;
        $childName = null;
        $appendedChild = null;
        $insertedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $refChild = $childList->item(3);
        $newdocFragment = $doc->createDocumentFragment();
        $newChild1 = $doc->createElement('br');
        $newChild2 = $doc->createElement('b');
        $appendedChild = $newdocFragment->appendChild($newChild1);
        $appendedChild = $newdocFragment->appendChild($newChild2);
        $insertedNode = $employeeNode->insertBefore($newdocFragment, $refChild);
        $child = $childList->item(3);
        $childName = $child->nodeName;
        $this->assertEqualsAutoCaseData('element', 'childName3', 'br', $childName);
        $child = $childList->item(4);
        $childName = $child->nodeName;
        $this->assertEqualsAutoCaseData('element', 'childName4', 'b', $childName);
    }
}
