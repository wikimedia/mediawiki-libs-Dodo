<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistreturnfirstitem.js.
class HcNodelistreturnfirstitemTest extends W3CTestHarness
{
    public function testHcNodelistreturnfirstitem()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodelistreturnfirstitem') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
        $child = null;
        $childName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(2);
        $employeeList = $employeeNode->childNodes;
        $child = $employeeList->item(0);
        $childName = $child->nodeName;
        if ('#text' == $childName) {
            $this->w3cAssertEquals('nodeName_w_space', '#text', $childName);
        } else {
            $this->w3cAssertEqualsAutoCase('element', 'nodeName_wo_space', 'em', $childName);
        }
    }
}
