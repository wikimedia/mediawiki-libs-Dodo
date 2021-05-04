<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodechildnodes.js.
class HcNodechildnodesTest extends W3cTestHarness
{
    public function testHcNodechildnodes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodechildnodes') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childNode = null;
        $childNodes = null;
        $nodeType = null;
        $childName = null;
        $actual = [];
        $expected = [];
        $expected[0] = 'em';
        $expected[1] = 'strong';
        $expected[2] = 'code';
        $expected[3] = 'sup';
        $expected[4] = 'var';
        $expected[5] = 'acronym';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childNodes = $employeeNode->childNodes;
        for ($indexN1006C = 0; $indexN1006C < count($childNodes); $indexN1006C++) {
            $childNode = $childNodes->item($indexN1006C);
            $nodeType = $childNode->nodeType;
            $childName = $childNode->nodeName;
            if (1 == $nodeType) {
                $actual[count($actual)] = $childName;
            } else {
                $this->assertEqualsData('textNodeType', 3, $nodeType);
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'elementNames', $expected, $actual);
    }
}
