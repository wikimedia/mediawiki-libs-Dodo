<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeappendchildnodeancestor.js.
class HcNodeappendchildnodeancestorTest extends W3CTestHarness
{
    public function testHcNodeappendchildnodeancestor()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 3;
        }
        $this->w3cAssertTrue('throw_HIERARCHY_REQUEST_ERR', $success);
    }
}
