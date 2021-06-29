<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLMetaElement01.js.
class HTMLMetaElement01Test extends W3CTestHarness
{
    public function testHTMLMetaElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLMetaElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcontent = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'meta');
        $nodeList = $doc->getElementsByTagName('meta');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vcontent = $testNode->content;
        $this->assertEqualsData('contentLink', 'text/html; CHARSET=utf-8', $vcontent);
    }
}
