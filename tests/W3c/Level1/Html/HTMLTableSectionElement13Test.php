<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableSectionElement13.js.
class HTMLTableSectionElement13Test extends W3cTestHarness
{
    public function testHTMLTableSectionElement13()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableSectionElement13') != null) {
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
        $nodeList = $doc->getElementsByTagName('thead');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $rowsnodeList = $testNode->rows;
        $vrows = count($rowsnodeList);
        $this->assertEqualsData('rowsLink', 1, $vrows);
    }
}
