<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement06.js.
class HTMLScriptElement06Test extends W3cTestHarness
{
    public function testHTMLScriptElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLScriptElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $htmlFor = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $htmlFor = $testNode->htmlFor;
    }
}
