<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbefore.js.
class HcNodeinsertbeforeTest extends W3cTestHarness
{
    public function testHcNodeinsertbefore()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeinsertbefore') != null) {
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
        $actual = [];
        $expected = [];
        $expected[0] = 'em';
        $expected[1] = 'strong';
        $expected[2] = 'code';
        $expected[3] = 'br';
        $expected[4] = 'sup';
        $expected[5] = 'var';
        $expected[6] = 'acronym';
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('sup');
        $refChild = $elementList->item(2);
        $employeeNode = $refChild->parentNode;
        $childList = $employeeNode->childNodes;
        $newChild = $doc->createElement('br');
        $insertedNode = $employeeNode->insertBefore($newChild, $refChild);
        for ($indexN10091 = 0; $indexN10091 < count($childList); $indexN10091++) {
            $child = $childList->item($indexN10091);
            $nodeType = $child->nodeType;
            if (1 == $nodeType) {
                $childName = $child->nodeName;
                $actual[count($actual)] = $childName;
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'nodeNames', $expected, $actual);
    }
}
