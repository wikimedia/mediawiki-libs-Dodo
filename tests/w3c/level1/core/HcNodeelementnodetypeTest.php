<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodetype.js.
class HcNodeelementnodetypeTest extends DomTestCase
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