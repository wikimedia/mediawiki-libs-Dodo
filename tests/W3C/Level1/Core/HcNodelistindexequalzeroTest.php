<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexequalzero.js.
class HcNodelistindexequalzeroTest extends W3CTestHarness
{
    public function testHcNodelistindexequalzero()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $child = $employeeList->item(0);
        $childName = $child->nodeName;
        if (13 == $length) {
            $this->assertEqualsData('childName_w_whitespace', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'childName_wo_whitespace', 'em', $childName);
        }
    }
}
