<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatasetnodevalue.js.
class HcCharacterdatasetnodevalueTest extends DomTestCase
{
    public function testHcCharacterdatasetnodevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_characterdatasetnodevalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childData = null;
        $childValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->nodeValue = 'Marilyn Martin';
        $childData = $child->data;
        $this->assertEqualsData('data', 'Marilyn Martin', $childData);
        $childValue = $child->nodeValue;
        $this->assertEqualsData('value', 'Marilyn Martin', $childValue);
    }
}