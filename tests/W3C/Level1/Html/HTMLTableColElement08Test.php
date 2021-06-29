<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableColElement08.js.
class HTMLTableColElement08Test extends W3CTestHarness
{
    public function testHTMLTableColElement08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTableColElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vspan = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablecol');
        $nodeList = $doc->getElementsByTagName('colgroup');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vspan = $testNode->span;
        $this->assertEqualsData('spanLink', 2, $vspan);
    }
}
