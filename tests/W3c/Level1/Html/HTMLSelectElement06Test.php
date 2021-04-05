<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement06.js.
class HTMLSelectElement06Test extends W3cTestHarness
{
    public function testHTMLSelectElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLSelectElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vform = null;
        $fNode = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->assertEqualsData('formLink', 'form1', $vform);
    }
}
