<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLIElement02.js.
class HTMLLIElement02Test extends W3cTestHarness
{
    public function testHTMLLIElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLLIElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vvalue = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'li');
        $nodeList = $doc->getElementsByTagName('li');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vvalue = $testNode->value;
        $this->assertEqualsData('valueLink', 2, $vvalue);
    }
}
