<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexgetlength.js.
class HcNodelistindexgetlengthTest extends W3CTestHarness
{
    public function testHcNodelistindexgetlength()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodelistindexgetlength') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
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
        if (6 == $length) {
            $this->assertEqualsData('length_wo_space', 6, $length);
        } else {
            $this->assertEqualsData('length_w_space', 13, $length);
        }
    }
}
