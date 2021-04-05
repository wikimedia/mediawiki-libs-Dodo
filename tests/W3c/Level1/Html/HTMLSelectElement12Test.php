<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement12.js.
class HTMLSelectElement12Test extends W3cTestHarness
{
    public function testHTMLSelectElement12()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLSelectElement12') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsize = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vsize = $testNode->size;
        $this->assertEqualsData('sizeLink', 1, $vsize);
    }
}
