<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodechildnodesappendchild.js.
class HcNodechildnodesappendchildTest extends W3CTestHarness
{
    public function testHcNodechildnodesappendchild()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodechildnodesappendchild') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $createdNode = null;
        $childNode = null;
        $childName = null;
        $childType = null;
        $textNode = null;
        $actual = [];
        $expected = [];
        $expected[0] = 'em';
        $expected[1] = 'strong';
        $expected[2] = 'code';
        $expected[3] = 'sup';
        $expected[4] = 'var';
        $expected[5] = 'acronym';
        $expected[6] = 'br';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $createdNode = $doc->createElement('br');
        $employeeNode = $employeeNode->appendChild($createdNode);
        for ($indexN10087 = 0; $indexN10087 < count($childList); $indexN10087++) {
            $childNode = $childList->item($indexN10087);
            $childName = $childNode->nodeName;
            $childType = $childNode->nodeType;
            if (1 == $childType) {
                $actual[count($actual)] = $childName;
            } else {
                $this->assertEqualsData('textNodeType', 3, $childType);
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'childElements', $expected, $actual);
    }
}
