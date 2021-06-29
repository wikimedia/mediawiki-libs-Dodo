<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textsplittextone.js.
class HcTextsplittextoneTest extends W3CTestHarness
{
    public function testHcTextsplittextone()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_textsplittextone') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $textNode = null;
        $splitNode = null;
        $secondPart = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(2);
        $textNode = $nameNode->firstChild;
        $splitNode = $textNode->splitText(7);
        $secondPart = $textNode->nextSibling;
        $value = $secondPart->nodeValue;
        $this->assertEqualsData('textSplitTextOneAssert', 'Jones', $value);
    }
}
