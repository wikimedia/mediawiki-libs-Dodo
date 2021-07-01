<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAnchorElement;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement07.js.
class HTMLAnchorElement07Test extends W3CTestHarness
{
    public function testHTMLAnchorElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLAnchorElement07') != null) {
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
        $doc = $this->load($docRef, 'doc', 'anchor');
        $nodeList = $doc->getElementsByTagName('a');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vrel = $testNode->rel;
        $this->w3cAssertEquals('relLink', 'GLOSSARY', $vrel);
    }
}
