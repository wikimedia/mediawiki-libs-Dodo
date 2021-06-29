<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement01.js.
class HTMLObjectElement01Test extends W3CTestHarness
{
    public function testHTMLObjectElement01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLObjectElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $fNode = null;
        $vform = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object2');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->assertEqualsData('idLink', 'object2', $vform);
    }
}
