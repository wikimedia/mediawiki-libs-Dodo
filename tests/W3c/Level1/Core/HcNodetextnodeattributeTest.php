<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodetextnodeattribute.js.
class HcNodetextnodeattributeTest extends W3cTestHarness
{
    public function testHcNodetextnodeattribute()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodetextnodeattribute') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testAddr = null;
        $textNode = null;
        $attrList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testAddr = $elementList[0];
        $textNode = $testAddr->firstChild;
        $attrList = $textNode->attributes;
        $this->assertNullData('text_attributes_is_null', $attrList);
    }
}
