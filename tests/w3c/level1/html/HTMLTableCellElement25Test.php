<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableCellElement25.js.
class HTMLTableCellElement25Test extends DomTestCase
{
    public function testHTMLTableCellElement25()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableCellElement25') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vscope = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablecell');
        $nodeList = $doc->getElementsByTagName('th');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList->item(1);
        $vscope = $testNode->scope;
        $this->assertEqualsData('scopeLink', 'col', $vscope);
    }
}