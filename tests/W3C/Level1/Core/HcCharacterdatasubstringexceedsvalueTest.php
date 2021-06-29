<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatasubstringexceedsvalue.js.
class HcCharacterdatasubstringexceedsvalueTest extends W3CTestHarness
{
    public function testHcCharacterdatasubstringexceedsvalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatasubstringexceedsvalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $substring = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $substring = $child->substringData(9, 10);
        $this->assertEqualsData('characterdataSubStringExceedsValueAssert', 'Martin', $substring);
    }
}
