<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforenodeancestor.js.
class HcNodeinsertbeforenodeancestorTest extends W3cTestHarness
{
    public function testHcNodeinsertbeforenodeancestor()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforenodeancestor') != null) {
            return;
        }
        $doc = null;
        $newChild = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $refChild = null;
        $insertedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newChild = $doc->documentElement;
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $refChild = $childList[0];
        $success = false;
        try {
            $insertedNode = $employeeNode->insertBefore($newChild, $refChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 3;
        }
        $this->assertTrueData('throw_HIERARCHY_REQUEST_ERR', $success);
    }
}
