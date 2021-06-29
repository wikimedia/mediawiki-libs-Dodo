<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLinkElement06.js.
class HTMLLinkElement06Test extends W3CTestHarness
{
    public function testHTMLLinkElement06()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLLinkElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vrel = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'link');
        $nodeList = $doc->getElementsByTagName('link');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vrel = $testNode->rel;
        $this->assertEqualsData('relLink', 'Glossary', $vrel);
    }
}
