<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textparseintolistofelements.js.
class HcTextparseintolistofelementsTest extends W3cTestHarness
{
    public function testHcTextparseintolistofelements()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_textparseintolistofelements') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $addressNode = null;
        $childList = null;
        $child = null;
        $value = null;
        $grandChild = null;
        $length = null;
        $result = [];
        $expectedNormal = [];
        $expectedNormal[0] = "β";
        $expectedNormal[1] = ' Dallas, ';
        $expectedNormal[2] = "γ";
        $expectedNormal[3] = "\n 98554";
        $expectedExpanded = [];
        $expectedExpanded[0] = "β Dallas, γ\n 98554";
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $addressNode = $elementList->item(1);
        $childList = $addressNode->childNodes;
        $length = count($childList);
        for ($indexN1007C = 0; $indexN1007C < count($childList); $indexN1007C++) {
            $child = $childList->item($indexN1007C);
            $value = $child->nodeValue;
            if ($value == null) {
                $grandChild = $child->firstChild;
                $this->assertNotNullData('grandChildNotNull', $grandChild);
                $value = $grandChild->nodeValue;
                $result[count($result)] = $value;
            } else {
                $result[count($result)] = $value;
            }
        }
        if (1 == $length) {
            $this->assertEqualsListData('assertEqCoalescing', $expectedExpanded, $result);
        } else {
            $this->assertEqualsListData('assertEqNormal', $expectedNormal, $result);
        }
    }
}
