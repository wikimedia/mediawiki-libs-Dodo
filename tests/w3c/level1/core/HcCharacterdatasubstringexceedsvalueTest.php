<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatasubstringexceedsvalue.js.
class HcCharacterdatasubstringexceedsvalueTest extends DomTestCase
{
    public function testHcCharacterdatasubstringexceedsvalue()
    {
        $builder = $this->getBuilder();
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
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $substring = $child->substringData(9, 10);
        $this->assertEqualsData('characterdataSubStringExceedsValueAssert', 'Martin', $substring);
    }
}