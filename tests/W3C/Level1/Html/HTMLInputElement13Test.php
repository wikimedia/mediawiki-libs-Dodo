<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement13.js.
class HTMLInputElement13Test extends W3CTestHarness
{
    public function testHTMLInputElement13()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLInputElement13') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsize = null;
        $doc = null;
        $domImpl = null;
        $hasHTML2 = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $domImpl = $doc->implementation;
        $hasHTML2 = $domImpl->hasFeature('HTML', '2.0');
        if (!$hasHTML2) {
            $nodeList = $doc->getElementsByTagName('input');
            $this->assertSizeData('Asize', 9, $nodeList);
            $testNode = $nodeList->item(0);
            $vsize = $testNode->size;
            $this->assertEqualsData('sizeLink', '25', $vsize);
        }
    }
}
