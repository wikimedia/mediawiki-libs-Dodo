<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableRowElement05.js.
class HTMLTableRowElement05Test extends DomTestCase
{
    public function testHTMLTableRowElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableRowElement05') != null) {
            return;
        }
        $nodeList = null;
        $cellsnodeList = null;
        $testNode = null;
        $vcells = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablerow');
        $nodeList = $doc->getElementsByTagName('tr');
        $this->assertSizeData('Asize', 5, $nodeList);
        $testNode = $nodeList->item(3);
        $cellsnodeList = $testNode->cells;
        $vcells = count($cellsnodeList);
        $this->assertEqualsData('cellsLink', 6, $vcells);
    }
}