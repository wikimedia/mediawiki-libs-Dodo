<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonenodefalse.js.
class HcNodeclonenodefalseTest extends DomTestCase
{
    public function testHcNodeclonenodefalse()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeclonenodefalse') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $clonedNode = null;
        $cloneName = null;
        $cloneChildren = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $clonedNode = $employeeNode->cloneNode(false);
        $cloneName = $clonedNode->nodeName;
        $this->assertEqualsAutoCaseData('element', 'strong', 'p', $cloneName);
        $cloneChildren = $clonedNode->childNodes;
        $length = count($cloneChildren);
        $this->assertEqualsData('length', 0, $length);
    }
}