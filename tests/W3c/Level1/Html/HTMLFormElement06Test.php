<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement06.js.
class HTMLFormElement06Test extends W3cTestHarness
{
    public function testHTMLFormElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $venctype = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $venctype = $testNode->enctype;
        $this->assertEqualsData('enctypeLink', 'application/x-www-form-urlencoded', $venctype);
    }
}
