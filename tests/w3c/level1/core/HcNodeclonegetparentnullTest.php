<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonegetparentnull.js.
class HcNodeclonegetparentnullTest extends DomTestCase
{
    public function testHcNodeclonegetparentnull()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeclonegetparentnull') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $clonedNode = null;
        $parentNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $clonedNode = $employeeNode->cloneNode(false);
        $parentNode = $clonedNode->parentNode;
        $this->assertNullData('nodeCloneGetParentNullAssert1', $parentNode);
    }
}