<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchildchildexists.js.
class HcNodeappendchildchildexistsTest extends DomTestCase
{
    public function testHcNodeappendchildchildexists()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeappendchildchildexists') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $childList = null;
        $childNode = null;
        $newChild = null;
        $memberNode = null;
        $memberName = null;
        $refreshedActual = [];
        $actual = [];
        $nodeType = null;
        $expected = [];
        $expected[0] = 'strong';
        $expected[1] = 'code';
        $expected[2] = 'sup';
        $expected[3] = 'var';
        $expected[4] = 'acronym';
        $expected[5] = 'em';
        $appendedChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $childNode = $elementList->item(1);
        $childList = $childNode->getElementsByTagName('*');
        $newChild = $childList[0];
        $appendedChild = $childNode->appendChild($newChild);
        for ($indexN10085 = 0; $indexN10085 < count($childList); $indexN10085++) {
            $memberNode = $childList->item($indexN10085);
            $memberName = $memberNode->nodeName;
            $actual[count($actual)] = $memberName;
        }
        $this->assertEqualsListAutoCaseData('element', 'liveByTagName', $expected, $actual);
        $childList = $childNode->childNodes;
        for ($indexN1009C = 0; $indexN1009C < count($childList); $indexN1009C++) {
            $memberNode = $childList->item($indexN1009C);
            $nodeType = $memberNode->nodeType;
            if (1 == $nodeType) {
                $memberName = $memberNode->nodeName;
                $refreshedActual[count($refreshedActual)] = $memberName;
            }
        }
        $this->assertEqualsListAutoCaseData('element', 'refreshedChildNodes', $expected, $refreshedActual);
    }
}