<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistreturnfirstitem.js.
class HcNodelistreturnfirstitemTest extends DomTestCase
{
    public function testHcNodelistreturnfirstitem()
    {
        $builder = $this->getBuilder();
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
        $child = $employeeList[0];
        $childName = $child->nodeName;
        if ('#text' == $childName) {
            $this->assertEqualsData('nodeName_w_space', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'nodeName_wo_space', 'em', $childName);
        }
    }
}