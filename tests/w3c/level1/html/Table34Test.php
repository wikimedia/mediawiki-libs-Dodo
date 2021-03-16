<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/table34.js.
class Table34Test extends DomTestCase
{
    public function testTable34()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'table34') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vborder = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $vborder = $testNode->border;
        $this->assertEqualsData('borderLink', '4', $vborder);
    }
}