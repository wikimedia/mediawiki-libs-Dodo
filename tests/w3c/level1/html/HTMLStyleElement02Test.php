<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLStyleElement02.js.
class HTMLStyleElement02Test extends DomTestCase
{
    public function testHTMLStyleElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLStyleElement02') != null) {
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
        $doc = $this->load($docRef, 'doc', 'style');
        $nodeList = $doc->getElementsByTagName('style');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vmedia = $testNode->media;
        $this->assertEqualsData('mediaLink', 'screen', $vmedia);
    }
}