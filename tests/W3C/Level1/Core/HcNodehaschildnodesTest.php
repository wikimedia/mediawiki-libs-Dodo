<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodehaschildnodes.js.
class HcNodehaschildnodesTest extends W3CTestHarness
{
    public function testHcNodehaschildnodes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodehaschildnodes') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $state = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $state = $employeeNode->hasChildNodes();
        $this->assertTrueData('nodeHasChildAssert1', $state);
    }
}
