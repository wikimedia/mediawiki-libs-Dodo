<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetnextsiblingnull.js.
class HcNodegetnextsiblingnullTest extends W3cTestHarness
{
    public function testHcNodegetnextsiblingnull()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodegetnextsiblingnull') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $lcNode = null;
        $nsNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $lcNode = $employeeNode->lastChild;
        $nsNode = $lcNode->nextSibling;
        $this->assertNullData('nodeGetNextSiblingNullAssert1', $nsNode);
    }
}
