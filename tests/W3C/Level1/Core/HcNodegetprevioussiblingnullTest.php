<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetprevioussiblingnull.js.
class HcNodegetprevioussiblingnullTest extends W3CTestHarness
{
    public function testHcNodegetprevioussiblingnull()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodegetprevioussiblingnull') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $fcNode = null;
        $psNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(2);
        $fcNode = $employeeNode->firstChild;
        $psNode = $fcNode->previousSibling;
        $this->assertNullData('nodeGetPreviousSiblingNullAssert1', $psNode);
    }
}
