<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLMetaElement02.js.
class HTMLMetaElement02Test extends W3CTestHarness
{
    public function testHTMLMetaElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLMetaElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhttpequiv = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'meta');
        $nodeList = $doc->getElementsByTagName('meta');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vhttpequiv = $testNode->httpEquiv;
        $this->assertEqualsData('httpEquivLink', 'Content-Type', $vhttpequiv);
    }
}
