<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeparentnode.js.
class HcNodeparentnodeTest extends DomTestCase
{
    public function testHcNodeparentnode()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeparentnode') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $parentNode = null;
        $parentName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $parentNode = $employeeNode->parentNode;
        $parentName = $parentNode->nodeName;
        $this->assertEqualsAutoCaseData('element', 'parentNodeName', 'body', $parentName);
    }
}