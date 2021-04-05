<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/area02.js.
class Area02Test extends W3cTestHarness
{
    public function testArea02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'area02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vnohref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vnohref = $testNode->noHref;
        $this->assertFalseData('noHrefLink', $vnohref);
    }
}
