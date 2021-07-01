<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFieldSetElement02.js.
class HTMLFieldSetElement02Test extends W3CTestHarness
{
    public function testHTMLFieldSetElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLFieldSetElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vform = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'fieldset');
        $nodeList = $doc->getElementsByTagName('fieldset');
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $vform = $testNode->form;
        $this->w3cAssertNull('formNullLink', $vform);
    }
}
