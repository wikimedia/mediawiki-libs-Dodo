<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableCellElement23.js.
class HTMLTableCellElement23Test extends W3cTestHarness
{
    public function testHTMLTableCellElement23()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableCellElement23') != null) {
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
        $nodeList = $doc->getElementsByTagName('th');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vrowspan = $testNode->rowSpan;
        $this->assertEqualsData('rowSpanLink', 1, $vrowspan);
    }
}
