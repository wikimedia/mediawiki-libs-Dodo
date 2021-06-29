<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLStyleElement01.js.
class HTMLStyleElement01Test extends W3CTestHarness
{
    public function testHTMLStyleElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLStyleElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdisabled = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'style');
        $nodeList = $doc->getElementsByTagName('style');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vdisabled = $testNode->disabled;
        $this->assertFalseData('disabledLink', $vdisabled);
    }
}
