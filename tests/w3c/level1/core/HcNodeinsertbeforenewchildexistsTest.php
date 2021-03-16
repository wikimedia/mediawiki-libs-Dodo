<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforenewchildexists.js.
class HcNodeinsertbeforenewchildexistsTest extends DomTestCase
{
    public function testHcNodeinsertbeforenewchildexists()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforenewchildexists') != null) {
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
        $expected = [];
        $expected[0] = 'strong';
        $expected[1] = 'code';
        $expected[2] = 'sup';
        $expected[3] = 'var';
        $expected[4] = 'em';
        $expected[5] = 'acronym';
        $result = [];
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->getElementsByTagName('*');
        $refChild = $childList->item(5);
        $newChild = $childList[0];
        $insertedNode = $employeeNode->insertBefore($newChild, $refChild);
        for ($indexN1008C = 0; $indexN1008C < count($childList); $indexN1008C++) {
            $child = $childList->item($indexN1008C);
            $nodeType = $child->nodeType;
            if (1 == $nodeType) {
                $childName = $child->nodeName;
                $result[count($result)] = $childName;
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'childNames', $expected, $result);
    }
}