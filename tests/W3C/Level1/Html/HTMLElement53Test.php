<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement53.js.
class HTMLElement53Test extends W3CTestHarness
{
    public function testHTMLElement53()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLElement53') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtitle = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('dd');
        $this->w3cAssertSize('Asize', 4, $nodeList);
        $testNode = $nodeList->item(0);
        $vtitle = $testNode->title;
        $this->w3cAssertEquals('titleLink', 'DD Element', $vtitle);
    }
}
