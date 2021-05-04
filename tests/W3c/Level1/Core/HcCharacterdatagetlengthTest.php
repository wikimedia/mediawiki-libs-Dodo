<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatagetlength.js.
class HcCharacterdatagetlengthTest extends W3cTestHarness
{
    public function testHcCharacterdatagetlength()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatagetlength') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childValue = null;
        $childLength = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $childValue = $child->data;
        $childLength = count($childValue);
        $this->assertEqualsData('characterdataGetLengthAssert', 15, $childLength);
    }
}
