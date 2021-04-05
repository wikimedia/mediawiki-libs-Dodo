<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/area01.js.
class Area01Test extends W3cTestHarness
{
    public function testArea01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'area01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcoords = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcoords = $testNode->coords;
        $this->assertEqualsData('coordsLink', '0,2,45,45', $vcoords);
    }
}
