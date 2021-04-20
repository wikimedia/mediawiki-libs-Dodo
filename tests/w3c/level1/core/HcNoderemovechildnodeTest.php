<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildnode.js.
class HcNoderemovechildnodeTest extends DomTestCase
{
    public function testHcNoderemovechildnode()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_noderemovechildnode') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $emList = null;
        $employeeNode = null;
        $childList = null;
        $oldChild = null;
        $child = null;
        $childName = null;
        $length = null;
        $removedChild = null;
        $removedName = null;
        $nodeType = null;
        $expected = [];
        $expected[0] = 'strong';
        $expected[1] = 'code';
        $expected[2] = 'sup';
        $expected[3] = 'var';
        $expected[4] = 'acronym';
        $actual = [];
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $emList = $employeeNode->getElementsByTagName('em');
        $oldChild = $emList[0];
        $removedChild = $employeeNode->removeChild($oldChild);
        $removedName = $removedChild->nodeName;
        $this->assertEqualsAutoCaseData('element', 'removedName', 'em', $removedName);
        for ($indexN10098 = 0; $indexN10098 < count($childList); $indexN10098++) {
            $child = $childList->item($indexN10098);
            $nodeType = $child->nodeType;
            $childName = $child->nodeName;
            if (1 == $nodeType) {
                $actual[count($actual)] = $childName;
            } else {
                $this->assertEqualsData('textNodeType', 3, $nodeType);
                $this->assertEqualsData('textNodeName', '#text', $childName);
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'childNames', $expected, $actual);
    }
}