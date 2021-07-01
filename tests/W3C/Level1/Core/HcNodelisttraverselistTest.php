<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelisttraverselist.js.
class HcNodelisttraverselistTest extends W3CTestHarness
{
    public function testHcNodelisttraverselist()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodelisttraverselist') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
        $child = null;
        $childName = null;
        $nodeType = null;
        $result = [];
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
        $employeeNode = $elementList->item(2);
        $employeeList = $employeeNode->childNodes;
        for ($indexN10073 = 0; $indexN10073 < count($employeeList); $indexN10073++) {
            $child = $employeeList->item($indexN10073);
            $nodeType = $child->nodeType;
            $childName = $child->nodeName;
            if (1 == $nodeType) {
                $result[count($result)] = $childName;
            } else {
                $this->w3cAssertEquals('textNodeType', 3, $nodeType);
                $this->w3cAssertEquals('textNodeName', '#text', $childName);
            }
        }
        $this->w3cAssertEqualsListAutoCase('element', 'nodeNames', $expected, $result);
    }
}
