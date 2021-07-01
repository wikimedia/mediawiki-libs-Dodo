<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildnode.js.
class HcNoderemovechildnodeTest extends W3CTestHarness
{
    public function testHcNoderemovechildnode()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $oldChild = $emList->item(0);
        $removedChild = $employeeNode->removeChild($oldChild);
        $removedName = $removedChild->nodeName;
        $this->w3cAssertEqualsAutoCase('element', 'removedName', 'em', $removedName);
        for ($indexN10098 = 0; $indexN10098 < count($childList); $indexN10098++) {
            $child = $childList->item($indexN10098);
            $nodeType = $child->nodeType;
            $childName = $child->nodeName;
            if (1 == $nodeType) {
                $actual[count($actual)] = $childName;
            } else {
                $this->w3cAssertEquals('textNodeType', 3, $nodeType);
                $this->w3cAssertEquals('textNodeName', '#text', $childName);
            }
        }
        $this->w3cAssertEqualsListAutoCase('element', 'childNames', $expected, $actual);
    }
}
