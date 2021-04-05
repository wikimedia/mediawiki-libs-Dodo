<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLabelElement04.js.
class HTMLLabelElement04Test extends W3cTestHarness
{
    public function testHTMLLabelElement04()
    {
        $builder = $this->getBuilder();
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
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vhtmlfor = $testNode->htmlFor;
        $this->assertEqualsData('htmlForLink', 'input1', $vhtmlfor);
    }
}
