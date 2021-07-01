<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonetruecopytext.js.
class HcNodeclonetruecopytextTest extends W3CTestHarness
{
    public function testHcNodeclonetruecopytext()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeclonetruecopytext') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $childNode = null;
        $clonedNode = null;
        $lastChildNode = null;
        $childValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('sup');
        $childNode = $elementList->item(1);
        $clonedNode = $childNode->cloneNode(true);
        $lastChildNode = $clonedNode->lastChild;
        $childValue = $lastChildNode->nodeValue;
        $this->w3cAssertEquals('cloneContainsText', '35,000', $childValue);
    }
}
