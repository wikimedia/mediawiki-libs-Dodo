<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement05.js.
class HTMLImageElement05Test extends W3cTestHarness
{
    public function testHTMLImageElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLImageElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vheight = null;
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
            $testNode = $nodeList[0];
            $vheight = $testNode->height;
            $this->assertEqualsData('heightLink', '47', $vheight);
        }
    }
}
