<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonetruecopytext.js.
class HcNodeclonetruecopytextTest extends W3cTestHarness
{
    public function testHcNodeclonetruecopytext()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('cloneContainsText', '35,000', $childValue);
    }
}
