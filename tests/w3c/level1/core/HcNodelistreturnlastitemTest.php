<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistreturnlastitem.js.
class HcNodelistreturnlastitemTest extends DomTestCase
{
    public function testHcNodelistreturnlastitem()
    {
        $builder = $this->getBuilder();
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
            $this->assertEqualsData('lastNodeName_w_whitespace', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'lastNodeName', 'acronym', $childName);
            $this->assertEqualsData('index', 5, $index);
        }
    }
}