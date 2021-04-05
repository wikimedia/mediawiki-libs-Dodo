<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLButtonElement06.js.
class HTMLButtonElement06Test extends W3cTestHarness
{
    public function testHTMLButtonElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLButtonElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtabindex = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vtabindex = $testNode->tabIndex;
        $this->assertEqualsData('tabIndexLink', 20, $vtabindex);
    }
}
