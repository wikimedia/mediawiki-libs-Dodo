<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildnewchildexists.js.
class HcNodereplacechildnewchildexistsTest extends DomTestCase
{
    public function testHcNodereplacechildnewchildexists()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodereplacechildnewchildexists') != null) {
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
        $childNode = null;
        $actual = [];
        $expected = [];
        $expected[0] = 'strong';
        $expected[1] = 'code';
        $expected[2] = 'sup';
        $expected[3] = 'var';
        $expected[4] = 'em';
        $replacedChild = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->getElementsByTagName('*');
        $newChild = $childList[0];
        $oldChild = $childList->item(5);
        $replacedChild = $employeeNode->replaceChild($newChild, $oldChild);
        $this->assertSameData('return_value_same', $oldChild, $replacedChild);
        for ($indexN10094 = 0; $indexN10094 < count($childList); $indexN10094++) {
            $childNode = $childList->item($indexN10094);
            $childName = $childNode->nodeName;
            $nodeType = $childNode->nodeType;
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