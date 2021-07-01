<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptGroupElement02.js.
class HTMLOptGroupElement02Test extends W3CTestHarness
{
    public function testHTMLOptGroupElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLOptGroupElement02') != null) {
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
        $doc = $this->load($docRef, 'doc', 'optgroup');
        $nodeList = $doc->getElementsByTagName('optgroup');
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vlabel = $testNode->label;
        $this->w3cAssertEquals('labelLink', 'Regular Employees', $vlabel);
    }
}
