<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableCellElement23.js.
class HTMLTableCellElement23Test extends W3CTestHarness
{
    public function testHTMLTableCellElement23()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertSize('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vrowspan = $testNode->rowSpan;
        $this->w3cAssertEquals('rowSpanLink', 1, $vrowspan);
    }
}
