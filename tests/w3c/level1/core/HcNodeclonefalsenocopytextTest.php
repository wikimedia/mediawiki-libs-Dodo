<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonefalsenocopytext.js.
class HcNodeclonefalsenocopytextTest extends DomTestCase
{
    public function testHcNodeclonefalsenocopytext()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeclonefalsenocopytext') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $childList = null;
        $childNode = null;
        $clonedNode = null;
        $lastChildNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $childList = $employeeNode->childNodes;
        $childNode = $childList->item(3);
        $clonedNode = $childNode->cloneNode(false);
        $lastChildNode = $clonedNode->lastChild;
        $this->assertNullData('nodeCloneFalseNoCopyTextAssert1', $lastChildNode);
    }
}