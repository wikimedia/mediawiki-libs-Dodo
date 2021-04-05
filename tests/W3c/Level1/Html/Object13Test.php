<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/object13.js.
class Object13Test extends W3cTestHarness
{
    public function testObject13()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object13') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vvspace = null;
        $doc = null;
        $domImpl = null;
        $hasHTML2 = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $domImpl = $doc->implementation;
        $hasHTML2 = $domImpl->hasFeature('HTML', '2.0');
        if (!$hasHTML2) {
            $nodeList = $doc->getElementsByTagName('object');
            $this->assertSizeData('Asize', 2, $nodeList);
            $testNode = $nodeList[0];
            $vvspace = $testNode->vspace;
            $this->assertEqualsData('vspaceLink', '0', $vvspace);
        }
    }
}
