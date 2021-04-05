<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement08.js.
class HTMLOptionElement08Test extends W3cTestHarness
{
    public function testHTMLOptionElement08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptionElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vselected = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->assertSizeData('Asize', 10, $nodeList);
        $testNode = $nodeList[0];
        $vselected = $testNode->defaultSelected;
        $this->assertTrueData('selectedLink', $vselected);
    }
}
