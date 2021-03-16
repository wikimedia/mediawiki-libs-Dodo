<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textwithnomarkup.js.
class HcTextwithnomarkupTest extends DomTestCase
{
    public function testHcTextwithnomarkup()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_textwithnomarkup') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $nodeV = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(2);
        $nodeV = $nameNode->firstChild;
        $value = $nodeV->nodeValue;
        $this->assertEqualsData('textWithNoMarkupAssert', "Roger\n Jones", $value);
    }
}