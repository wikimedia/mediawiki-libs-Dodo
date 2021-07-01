<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFieldSetElement01.js.
class HTMLFieldSetElement01Test extends W3CTestHarness
{
    public function testHTMLFieldSetElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->w3cAssertEquals('formLink', 'form2', $vform);
    }
}
