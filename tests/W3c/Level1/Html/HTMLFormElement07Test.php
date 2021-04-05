<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement07.js.
class HTMLFormElement07Test extends W3cTestHarness
{
    public function testHTMLFormElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmethod = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vmethod = $testNode->method;
        $this->assertEqualsData('methodLink', 'post', $vmethod);
    }
}
