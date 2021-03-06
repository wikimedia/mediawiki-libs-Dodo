<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement02.js.
class HTMLInputElement02Test extends W3CTestHarness
{
    public function testHTMLInputElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLInputElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdefaultchecked = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->w3cAssertSize('Asize', 9, $nodeList);
        $testNode = $nodeList->item(3);
        $vdefaultchecked = $testNode->defaultChecked;
        $this->w3cAssertTrue('defaultCheckedLink', $vdefaultchecked);
    }
}
