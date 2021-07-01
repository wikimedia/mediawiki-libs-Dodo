<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/table34.js.
class Table34Test extends W3CTestHarness
{
    public function testTable34()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertSize('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $vborder = $testNode->border;
        $this->w3cAssertEquals('borderLink', '4', $vborder);
    }
}
