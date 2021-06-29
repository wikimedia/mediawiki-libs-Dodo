<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableSectionElement14.js.
class HTMLTableSectionElement14Test extends W3CTestHarness
{
    public function testHTMLTableSectionElement14()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTableSectionElement14') != null) {
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
        $nodeList = $doc->getElementsByTagName('tfoot');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $rowsnodeList = $testNode->rows;
        $vrows = count($rowsnodeList);
        $this->assertEqualsData('rowsLink', 1, $vrows);
    }
}
