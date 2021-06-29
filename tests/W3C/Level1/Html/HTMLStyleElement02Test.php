<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLStyleElement02.js.
class HTMLStyleElement02Test extends W3CTestHarness
{
    public function testHTMLStyleElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLStyleElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmedia = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'style');
        $nodeList = $doc->getElementsByTagName('style');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vmedia = $testNode->media;
        $this->assertEqualsData('mediaLink', 'screen', $vmedia);
    }
}