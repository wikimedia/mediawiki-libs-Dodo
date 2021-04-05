<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodetextnodetype.js.
class HcNodetextnodetypeTest extends W3cTestHarness
{
    public function testHcNodetextnodetype()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodetextnodetype') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddr = null;
        $textNode = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddr = $elementList[0];
        $textNode = $testAddr->firstChild;
        $nodeType = $textNode->nodeType;
        $this->assertEqualsData('nodeTextNodeTypeAssert1', 3, $nodeType);
    }
}
