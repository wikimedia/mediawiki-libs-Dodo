<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagnameaccessnodelist.js.
class HcElementgetelementsbytagnameaccessnodelistTest extends W3CTestHarness
{
    public function testHcElementgetelementsbytagnameaccessnodelist()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementgetelementsbytagnameaccessnodelist') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testEmployee = null;
        $firstC = null;
        $childName = null;
        $nodeType = null;
        $employeeIDNode = null;
        $employeeID = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $testEmployee = $elementList->item(3);
        $firstC = $testEmployee->firstChild;
        $nodeType = $firstC->nodeType;
        while (3 == $nodeType) {
            $firstC = $firstC->nextSibling;
            $nodeType = $firstC->nodeType;
        }
        $childName = $firstC->nodeName;
        $this->w3cAssertEqualsAutoCase('element', 'childName', 'em', $childName);
        $employeeIDNode = $firstC->firstChild;
        $employeeID = $employeeIDNode->nodeValue;
        $this->w3cAssertEquals('employeeID', 'EMP0004', $employeeID);
    }
}
