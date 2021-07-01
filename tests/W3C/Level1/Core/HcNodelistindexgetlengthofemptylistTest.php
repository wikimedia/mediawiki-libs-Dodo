<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexgetlengthofemptylist.js.
class HcNodelistindexgetlengthofemptylistTest extends W3CTestHarness
{
    public function testHcNodelistindexgetlengthofemptylist()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodelistindexgetlengthofemptylist') != null) {
            return;
        }
        $doc = null;
        $emList = null;
        $emNode = null;
        $textNode = null;
        $textList = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $emList = $doc->getElementsByTagName('em');
        $emNode = $emList->item(2);
        $textNode = $emNode->firstChild;
        $textList = $textNode->childNodes;
        $length = count($textList);
        $this->w3cAssertEquals('length', 0, $length);
    }
}
