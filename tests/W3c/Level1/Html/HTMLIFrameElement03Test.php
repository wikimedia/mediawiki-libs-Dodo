<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLIFrameElement03.js.
class HTMLIFrameElement03Test extends W3cTestHarness
{
    public function testHTMLIFrameElement03()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLIFrameElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vheight = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'iframe');
        $nodeList = $doc->getElementsByTagName('iframe');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vheight = $testNode->height;
        $this->assertEqualsData('heightLink', '50', $vheight);
    }
}
