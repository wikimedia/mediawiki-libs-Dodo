<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement12.js.
class HTMLImageElement12Test extends W3CTestHarness
{
    public function testHTMLImageElement12()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLImageElement12') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vwidth = null;
        $doc = null;
        $domImpl = null;
        $hasHTML2 = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'img');
        $domImpl = $doc->implementation;
        $hasHTML2 = $domImpl->hasFeature('HTML', '2.0');
        if (!$hasHTML2) {
            $nodeList = $doc->getElementsByTagName('img');
            $this->assertSizeData('Asize', 1, $nodeList);
            $testNode = $nodeList->item(0);
            $vwidth = $testNode->width;
            $this->assertEqualsData('widthLink', '115', $vwidth);
        }
    }
}
