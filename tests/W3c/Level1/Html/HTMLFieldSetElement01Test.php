<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFieldSetElement01.js.
class HTMLFieldSetElement01Test extends W3cTestHarness
{
    public function testHTMLFieldSetElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFieldSetElement01') != null) {
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
        $doc = $this->load($docRef, 'doc', 'fieldset');
        $nodeList = $doc->getElementsByTagName('fieldset');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->assertEqualsData('formLink', 'form2', $vform);
    }
}
