<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement14.js.
class HTMLInputElement14Test extends W3CTestHarness
{
    public function testHTMLInputElement14()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLInputElement14') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->w3cAssertSize('Asize', 9, $nodeList);
        $testNode = $nodeList->item(7);
        $vsrc = $testNode->src;
        $this->w3cAssertURIEquals('srcLink', null, null, null, 'submit.gif', null, null, null, null, $vsrc);
    }
}
