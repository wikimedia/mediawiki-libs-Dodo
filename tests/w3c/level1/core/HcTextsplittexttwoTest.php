<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textsplittexttwo.js.
class HcTextsplittexttwoTest extends DomTestCase
{
    public function testHcTextsplittexttwo()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_textsplittexttwo') != null) {
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
        $splitNode = $textNode->splitText(5);
        $value = $textNode->nodeValue;
        $this->assertEqualsData('textSplitTextTwoAssert', 'Roger', $value);
    }
}