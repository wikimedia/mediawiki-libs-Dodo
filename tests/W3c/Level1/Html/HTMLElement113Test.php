<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement113.js.
class HTMLElement113Test extends W3cTestHarness
{
    public function testHTMLElement113()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLElement113') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdir = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('noframes');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vdir = $testNode->dir;
        $this->assertEqualsData('dirLink', 'ltr', $vdir);
    }
}
