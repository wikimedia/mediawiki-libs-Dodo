<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetfirstchild.js.
class HcNodegetfirstchildTest extends W3CTestHarness
{
    public function testHcNodegetfirstchild()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
            $this->w3cAssertEquals('firstChild_w_whitespace', '#text', $childName);
        } else {
            $this->w3cAssertEqualsAutoCase('element', 'firstChild_wo_whitespace', 'em', $childName);
        }
    }
}
