<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/object08.js.
class Object08Test extends W3CTestHarness
{
    public function testObject08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'object08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhspace = null;
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
            $testNode = $nodeList->item(0);
            $vhspace = $testNode->hspace;
            $this->assertEqualsData('hspaceLink', '0', $vhspace);
        }
    }
}
