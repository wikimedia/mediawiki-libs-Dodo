<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchildchildexists.js.
class HcNodeappendchildchildexistsTest extends W3CTestHarness
{
    public function testHcNodeappendchildchildexists()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $newChild = $childList->item(0);
        $appendedChild = $childNode->appendChild($newChild);
        for ($indexN10085 = 0; $indexN10085 < count($childList); $indexN10085++) {
            $memberNode = $childList->item($indexN10085);
            $memberName = $memberNode->nodeName;
            $actual[count($actual)] = $memberName;
        }
        $this->w3cAssertEqualsListAutoCase('element', 'liveByTagName', $expected, $actual);
        $childList = $childNode->childNodes;
        for ($indexN1009C = 0; $indexN1009C < count($childList); $indexN1009C++) {
            $memberNode = $childList->item($indexN1009C);
            $nodeType = $memberNode->nodeType;
            if (1 == $nodeType) {
                $memberName = $memberNode->nodeName;
                $refreshedActual[count($refreshedActual)] = $memberName;
            }
        }
        $this->w3cAssertEqualsListAutoCase('element', 'refreshedChildNodes', $expected, $refreshedActual);
    }
}
