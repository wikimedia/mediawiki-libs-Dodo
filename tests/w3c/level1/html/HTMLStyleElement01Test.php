<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLStyleElement01.js.
class HTMLStyleElement01Test extends DomTestCase
{
    public function testHTMLStyleElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLStyleElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdisabled = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'style');
        $nodeList = $doc->getElementsByTagName('style');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdisabled = $testNode->disabled;
        $this->assertFalseData('disabledLink', $vdisabled);
    }
}