<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement06.js.
class HTMLTableElement06Test extends W3cTestHarness
{
    public function testHTMLTableElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsection = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vsection = $testNode->tFoot;
        $this->assertNullData('sectionLink', $vsection);
    }
}
