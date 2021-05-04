<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTitleElement01.js.
class HTMLTitleElement01Test extends W3cTestHarness
{
    public function testHTMLTitleElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTitleElement01') != null) {
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
        $doc = $this->load($docRef, 'doc', 'title');
        $nodeList = $doc->getElementsByTagName('title');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vtext = $testNode->text;
        $this->assertEqualsData('textLink', 'NIST DOM HTML Test - TITLE', $vtext);
    }
}
