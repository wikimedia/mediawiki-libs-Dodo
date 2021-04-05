<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAnchorElement;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement07.js.
class HTMLAnchorElement07Test extends W3cTestHarness
{
    public function testHTMLAnchorElement07()
    {
        $builder = $this->getBuilder();
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
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vrel = $testNode->rel;
        $this->assertEqualsData('relLink', 'GLOSSARY', $vrel);
    }
}
