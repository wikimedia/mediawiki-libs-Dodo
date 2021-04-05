<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLinkElement05.js.
class HTMLLinkElement05Test extends W3cTestHarness
{
    public function testHTMLLinkElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLLinkElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmedia = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'link');
        $nodeList = $doc->getElementsByTagName('link');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vmedia = $testNode->media;
        $this->assertEqualsData('mediaLink', 'screen', $vmedia);
    }
}
