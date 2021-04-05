<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetelementsbytagnamevalue.js.
class HcDocumentgetelementsbytagnamevalueTest extends W3cTestHarness
{
    public function testHcDocumentgetelementsbytagnamevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentgetelementsbytagnamevalue') != null) {
            return;
        }
        $doc = null;
        $nameList = null;
        $nameNode = null;
        $firstChild = null;
        $childValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $nameList = $doc->getElementsByTagName('strong');
        $nameNode = $nameList->item(3);
        $firstChild = $nameNode->firstChild;
        $childValue = $firstChild->nodeValue;
        $this->assertEqualsData('documentGetElementsByTagNameValueAssert', 'Jeny Oconnor', $childValue);
    }
}
