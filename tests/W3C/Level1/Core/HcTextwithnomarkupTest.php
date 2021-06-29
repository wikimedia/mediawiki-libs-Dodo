<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_textwithnomarkup.js.
class HcTextwithnomarkupTest extends W3CTestHarness
{
    public function testHcTextwithnomarkup()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
