<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement01.js.
class HTMLScriptElement01Test extends W3CTestHarness
{
    public function testHTMLScriptElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLScriptElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtext = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vtext = $testNode->text;
        $this->assertEqualsData('textLink', 'var a=2;', $vtext);
    }
}
