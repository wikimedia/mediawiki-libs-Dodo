<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodetype.js.
class HcNodeelementnodetypeTest extends W3CTestHarness
{
    public function testHcNodeelementnodetype()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
