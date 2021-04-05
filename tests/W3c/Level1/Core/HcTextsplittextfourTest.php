<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textsplittextfour.js.
class HcTextsplittextfourTest extends W3cTestHarness
{
    public function testHcTextsplittextfour()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_textsplittextfour') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $addressNode = null;
        $textNode = null;
        $splitNode = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $addressNode = $elementList[0];
        $textNode = $addressNode->firstChild;
        $splitNode = $textNode->splitText(30);
        $value = $splitNode->nodeValue;
        $this->assertEqualsData('textSplitTextFourAssert', '98551', $value);
    }
}
