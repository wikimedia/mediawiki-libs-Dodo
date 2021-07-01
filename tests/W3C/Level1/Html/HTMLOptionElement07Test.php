<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement07.js.
class HTMLOptionElement07Test extends W3CTestHarness
{
    public function testHTMLOptionElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLOptionElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlabel = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->w3cAssertSize('Asize', 10, $nodeList);
        $testNode = $nodeList->item(1);
        $vlabel = $testNode->label;
        $this->w3cAssertEquals('labelLink', 'l1', $vlabel);
    }
}
