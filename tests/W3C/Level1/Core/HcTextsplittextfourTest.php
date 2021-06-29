<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textsplittextfour.js.
class HcTextsplittextfourTest extends W3CTestHarness
{
    public function testHcTextsplittextfour()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $addressNode = $elementList->item(0);
        $textNode = $addressNode->firstChild;
        $splitNode = $textNode->splitText(30);
        $value = $splitNode->nodeValue;
        $this->assertEqualsData('textSplitTextFourAssert', '98551', $value);
    }
}
