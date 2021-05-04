<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableRowElement05.js.
class HTMLTableRowElement05Test extends W3cTestHarness
{
    public function testHTMLTableRowElement05()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
