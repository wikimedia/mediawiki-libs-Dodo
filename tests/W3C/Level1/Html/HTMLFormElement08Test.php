<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement08.js.
class HTMLFormElement08Test extends W3CTestHarness
{
    public function testHTMLFormElement08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLFormElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtarget = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form2');
        $nodeList = $doc->getElementsByTagName('form');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vtarget = $testNode->target;
        $this->w3cAssertEquals('targetLink', 'dynamic', $vtarget);
    }
}
