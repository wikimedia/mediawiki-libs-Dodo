<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLabelElement04.js.
class HTMLLabelElement04Test extends W3CTestHarness
{
    public function testHTMLLabelElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLLabelElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhtmlfor = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'label');
        $nodeList = $doc->getElementsByTagName('label');
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vhtmlfor = $testNode->htmlFor;
        $this->w3cAssertEquals('htmlForLink', 'input1', $vhtmlfor);
    }
}
