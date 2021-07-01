<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodechildnodesempty.js.
class HcNodechildnodesemptyTest extends W3CTestHarness
{
    public function testHcNodechildnodesempty()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodechildnodesempty') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $childList = null;
        $employeeNode = null;
        $textNode = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('em');
        $employeeNode = $elementList->item(1);
        $textNode = $employeeNode->firstChild;
        $childList = $textNode->childNodes;
        $length = count($childList);
        $this->w3cAssertEquals('length_zero', 0, $length);
    }
}
