<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement03.js.
class HTMLSelectElement03Test extends W3cTestHarness
{
    public function testHTMLSelectElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLSelectElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vselectedindex = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $vselectedindex = $testNode->selectedIndex;
    }
}
