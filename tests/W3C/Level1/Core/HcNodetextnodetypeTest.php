<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodetextnodetype.js.
class HcNodetextnodetypeTest extends W3CTestHarness
{
    public function testHcNodetextnodetype()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $testAddr = $elementList->item(0);
        $textNode = $testAddr->firstChild;
        $nodeType = $textNode->nodeType;
        $this->w3cAssertEquals('nodeTextNodeTypeAssert1', 3, $nodeType);
    }
}
