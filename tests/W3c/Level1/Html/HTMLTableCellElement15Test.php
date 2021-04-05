<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableCellElement15.js.
class HTMLTableCellElement15Test extends W3cTestHarness
{
    public function testHTMLTableCellElement15()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableCellElement15') != null) {
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
        $nodeList = $doc->getElementsByTagName('th');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vcolspan = $testNode->colSpan;
        $this->assertEqualsData('colSpanLink', 1, $vcolspan);
    }
}
