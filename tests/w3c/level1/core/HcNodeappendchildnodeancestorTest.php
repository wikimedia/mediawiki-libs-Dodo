<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchildnodeancestor.js.
class HcNodeappendchildnodeancestorTest extends DomTestCase
{
    public function testHcNodeappendchildnodeancestor()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeappendchildnodeancestor') != null) {
            return;
        }
        $doc = null;
        $newChild = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $oldChild = null;
        $appendedChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newChild = $doc->documentElement;
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $success = false;
        try {
            $appendedChild = $employeeNode->appendChild($newChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 3;
        }
        $this->assertTrueData('throw_HIERARCHY_REQUEST_ERR', $success);
    }
}