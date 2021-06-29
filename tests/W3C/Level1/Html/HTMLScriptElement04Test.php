<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement04.js.
class HTMLScriptElement04Test extends W3CTestHarness
{
    public function testHTMLScriptElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLScriptElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vsrc = $testNode->src;
        $this->assertURIEqualsData('srcLink', null, null, null, 'script1.js', null, null, null, null, $vsrc);
    }
}
