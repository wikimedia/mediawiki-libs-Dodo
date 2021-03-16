<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodetextnodevalue.js.
class HcNodetextnodevalueTest extends DomTestCase
{
    public function testHcNodetextnodevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodetextnodevalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddr = null;
        $textNode = null;
        $textValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddr = $elementList[0];
        $textNode = $testAddr->firstChild;
        $textValue = $textNode->nodeValue;
        $this->assertEqualsData('textNodeValue', '1230 North Ave. Dallas, Texas 98551', $textValue);
    }
}