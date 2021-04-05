<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement140.js.
class HTMLElement140Test extends W3cTestHarness
{
    public function testHTMLElement140()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement140') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vclassname = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('dd');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList[0];
        $vclassname = $testNode->className;
        $this->assertEqualsData('classNameLink', 'DD-class', $vclassname);
    }
}
