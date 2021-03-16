<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodehaschildnodes.js.
class HcNodehaschildnodesTest extends DomTestCase
{
    public function testHcNodehaschildnodes()
    {
        $builder = $this->getBuilder();
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