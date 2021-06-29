<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement09.js.
class HTMLImageElement09Test extends W3CTestHarness
{
    public function testHTMLImageElement09()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLImageElement09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'img');
        $nodeList = $doc->getElementsByTagName('img');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vsrc = $testNode->src;
        $this->assertURIEqualsData('srcLink', null, null, null, 'dts.gif', null, null, null, null, $vsrc);
    }
}
