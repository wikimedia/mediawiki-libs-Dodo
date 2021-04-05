<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/button06.js.
class Button06Test extends W3cTestHarness
{
    public function testButton06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'button06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtabIndex = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vtabIndex = $testNode->tabIndex;
        $this->assertEqualsData('tabIndexLink', 20, $vtabIndex);
    }
}
