<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetprevioussiblingnull.js.
class HcNodegetprevioussiblingnullTest extends DomTestCase
{
    public function testHcNodegetprevioussiblingnull()
    {
        $builder = $this->getBuilder();
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