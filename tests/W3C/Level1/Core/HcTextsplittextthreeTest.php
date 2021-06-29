<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textsplittextthree.js.
class HcTextsplittextthreeTest extends W3CTestHarness
{
    public function testHcTextsplittextthree()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_textsplittextthree') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $textNode = null;
        $splitNode = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(2);
        $textNode = $nameNode->firstChild;
        $splitNode = $textNode->splitText(6);
        $value = $splitNode->nodeValue;
        $this->assertEqualsData('textSplitTextThreeAssert', ' Jones', $value);
    }
}
