<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistreturnlastitem.js.
class HcNodelistreturnlastitemTest extends W3CTestHarness
{
    public function testHcNodelistreturnlastitem()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodelistreturnlastitem') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
        $child = null;
        $childName = null;
        $index = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(2);
        $employeeList = $employeeNode->childNodes;
        $index = count($employeeList);
        $index -= 1;
        $child = $employeeList->item($index);
        $childName = $child->nodeName;
        if (12 == $index) {
            $this->w3cAssertEquals('lastNodeName_w_whitespace', '#text', $childName);
        } else {
            $this->w3cAssertEqualsAutoCase('element', 'lastNodeName', 'acronym', $childName);
            $this->w3cAssertEquals('index', 5, $index);
        }
    }
}
