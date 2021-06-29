<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement07.js.
class HTMLTableElement07Test extends W3CTestHarness
{
    public function testHTMLTableElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTableElement07') != null) {
            return;
        }
        $nodeList = null;
        $rowsnodeList = null;
        $testNode = null;
        $doc = null;
        $rowName = null;
        $vrow = null;
        $result = [];
        $expectedOptions = [];
        $expectedOptions[0] = 'tr';
        $expectedOptions[1] = 'tr';
        $expectedOptions[2] = 'tr';
        $expectedOptions[3] = 'tr';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $rowsnodeList = $testNode->rows;
        for ($indexN65641 = 0; $indexN65641 < count($rowsnodeList); $indexN65641++) {
            $vrow = $rowsnodeList->item($indexN65641);
            $rowName = $vrow->nodeName;
            $result[count($result)] = $rowName;
        }
        $this->assertEqualsListAutoCaseData('element', 'rowsLink', $expectedOptions, $result);
    }
}
