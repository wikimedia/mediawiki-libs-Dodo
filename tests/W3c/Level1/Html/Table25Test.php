<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/table25.js.
class Table25Test extends W3cTestHarness
{
    public function testTable25()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'table25') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcolspan = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablecell');
        $nodeList = $doc->getElementsByTagName('td');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vcolspan = $testNode->colSpan;
        $this->assertEqualsData('colSpanLink', 1, $vcolspan);
    }
}
