<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodetype.js.
class HcNodeelementnodetypeTest extends W3cTestHarness
{
    public function testHcNodeelementnodetype()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeelementnodetype') != null) {
            return;
        }
        $doc = null;
        $rootNode = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $rootNode = $doc->documentElement;
        $nodeType = $rootNode->nodeType;
        $this->assertEqualsData('nodeElementNodeTypeAssert1', 1, $nodeType);
    }
}
