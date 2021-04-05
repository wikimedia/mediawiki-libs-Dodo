<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptGroupElement01.js.
class HTMLOptGroupElement01Test extends W3cTestHarness
{
    public function testHTMLOptGroupElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptGroupElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdisabled = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'optgroup');
        $nodeList = $doc->getElementsByTagName('optgroup');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $vdisabled = $testNode->disabled;
        $this->assertTrueData('disabledLink', $vdisabled);
    }
}
