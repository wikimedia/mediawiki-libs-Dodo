<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexequalzero.js.
class HcNodelistindexequalzeroTest extends DomTestCase
{
    public function testHcNodelistindexequalzero()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodelistindexequalzero') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
        $child = null;
        $childName = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(2);
        $employeeList = $employeeNode->childNodes;
        $length = count($employeeList);
        $child = $employeeList[0];
        $childName = $child->nodeName;
        if (13 == $length) {
            $this->assertEqualsData('childName_w_whitespace', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'childName_wo_whitespace', 'em', $childName);
        }
    }
}