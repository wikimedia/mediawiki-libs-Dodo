<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object08.js.
class Object08Test extends DomTestCase
{
    public function testObject08()
    {
        $builder = $this->getBuilder();
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
            $testNode = $nodeList[0];
            $vhspace = $testNode->hspace;
            $this->assertEqualsData('hspaceLink', '0', $vhspace);
        }
    }
}