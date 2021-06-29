<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableSectionElement15.js.
class HTMLTableSectionElement15Test extends W3CTestHarness
{
    public function testHTMLTableSectionElement15()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTableSectionElement15') != null) {
            return;
        }
        $nodeList = null;
        $rowsnodeList = null;
        $testNode = null;
        $vrows = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablesection');
        $nodeList = $doc->getElementsByTagName('tbody');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $rowsnodeList = $testNode->rows;
        $vrows = count($rowsnodeList);
        $this->assertEqualsData('rowsLink', 2, $vrows);
    }
}
