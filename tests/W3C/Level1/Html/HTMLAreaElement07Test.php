<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAreaElement;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement07.js.
class HTMLAreaElement07Test extends W3CTestHarness
{
    public function testHTMLAreaElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLAreaElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtabindex = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vtabindex = $testNode->tabIndex;
        $this->w3cAssertEquals('tabIndexLink', 10, $vtabindex);
    }
}
