<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchilddocfragment.js.
class HcNodeappendchilddocfragmentTest extends W3cTestHarness
{
    public function testHcNodeappendchilddocfragment()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeappendchilddocfragment') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $newdocFragment = null;
        $newChild1 = null;
        $newChild2 = null;
        $child = null;
        $childName = null;
        $result = [];
        $appendedChild = null;
        $nodeType = null;
        $expected = [];
        $expected[0] = 'em';
        $expected[1] = 'strong';
        $expected[2] = 'code';
        $expected[3] = 'sup';
        $expected[4] = 'var';
        $expected[5] = 'acronym';
        $expected[6] = 'br';
        $expected[7] = 'b';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $newdocFragment = $doc->createDocumentFragment();
        $newChild1 = $doc->createElement('br');
        $newChild2 = $doc->createElement('b');
        $appendedChild = $newdocFragment->appendChild($newChild1);
        $appendedChild = $newdocFragment->appendChild($newChild2);
        $appendedChild = $employeeNode->appendChild($newdocFragment);
        for ($indexN100A2 = 0; $indexN100A2 < count($childList); $indexN100A2++) {
            $child = $childList->item($indexN100A2);
            $nodeType = $child->nodeType;
            if (1 == $nodeType) {
                $childName = $child->nodeName;
                $result[count($result)] = $childName;
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'nodeNames', $expected, $result);
    }
}
