<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLModElement01.js.
class HTMLModElement01Test extends W3CTestHarness
{
    public function testHTMLModElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLModElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcite = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'mod');
        $nodeList = $doc->getElementsByTagName('ins');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vcite = $testNode->cite;
        $this->w3cAssertURIEquals('citeLink', null, null, null, 'ins-reasons.html', null, null, null, null, $vcite);
    }
}
