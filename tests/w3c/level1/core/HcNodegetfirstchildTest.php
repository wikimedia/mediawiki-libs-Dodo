<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetfirstchild.js.
class HcNodegetfirstchildTest extends DomTestCase
{
    public function testHcNodegetfirstchild()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodegetfirstchild') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $fchildNode = null;
        $childName = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $fchildNode = $employeeNode->firstChild;
        $childName = $fchildNode->nodeName;
        if ('#text' == $childName) {
            $this->assertEqualsData('firstChild_w_whitespace', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'firstChild_wo_whitespace', 'em', $childName);
        }
    }
}