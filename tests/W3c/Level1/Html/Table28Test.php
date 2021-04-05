<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/table28.js.
class Table28Test extends W3cTestHarness
{
    public function testTable28()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'table28') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vrowspan = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablecell');
        $nodeList = $doc->getElementsByTagName('td');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vrowspan = $testNode->rowSpan;
        $this->assertEqualsData('rowSpanLink', 1, $vrowspan);
    }
}
