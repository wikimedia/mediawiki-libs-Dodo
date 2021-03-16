<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableSectionElement14.js.
class HTMLTableSectionElement14Test extends DomTestCase
{
    public function testHTMLTableSectionElement14()
    {
        $builder = $this->getBuilder();
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
        $testNode = $nodeList[0];
        $rowsnodeList = $testNode->rows;
        $vrows = count($rowsnodeList);
        $this->assertEqualsData('rowsLink', 1, $vrows);
    }
}