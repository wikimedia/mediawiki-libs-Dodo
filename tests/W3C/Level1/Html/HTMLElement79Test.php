<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement79.js.
class HTMLElement79Test extends W3CTestHarness
{
    public function testHTMLElement79()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLElement79') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlang = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('cite');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vlang = $testNode->lang;
        $this->assertEqualsData('langLink', 'en', $vlang);
    }
}
