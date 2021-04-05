<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodechildnodesempty.js.
class HcNodechildnodesemptyTest extends W3cTestHarness
{
    public function testHcNodechildnodesempty()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('length_zero', 0, $length);
    }
}
