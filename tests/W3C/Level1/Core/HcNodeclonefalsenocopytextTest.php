<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonefalsenocopytext.js.
class HcNodeclonefalsenocopytextTest extends W3CTestHarness
{
    public function testHcNodeclonefalsenocopytext()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
