<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOListElement02.js.
class HTMLOListElement02Test extends W3cTestHarness
{
    public function testHTMLOListElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLOListElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vstart = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'olist');
        $nodeList = $doc->getElementsByTagName('ol');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vstart = $testNode->start;
        $this->assertEqualsData('startLink', 1, $vstart);
    }
}
