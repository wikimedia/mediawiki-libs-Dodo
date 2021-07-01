<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement07.js.
class HTMLImageElement07Test extends W3CTestHarness
{
    public function testHTMLImageElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLImageElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vismap = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'img');
        $nodeList = $doc->getElementsByTagName('img');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vismap = $testNode->isMap;
        $this->w3cAssertFalse('isMapLink', $vismap);
    }
}
