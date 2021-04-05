<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/anchor04.js.
class Anchor04Test extends W3cTestHarness
{
    public function testAnchor04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'anchor04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vhref = $testNode->href;
        $this->assertURIEqualsData('hrefLink', null, null, null, 'submit.gif', null, null, null, true, $vhref);
    }
}
