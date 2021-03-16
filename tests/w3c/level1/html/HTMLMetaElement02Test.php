<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLMetaElement02.js.
class HTMLMetaElement02Test extends DomTestCase
{
    public function testHTMLMetaElement02()
    {
        $builder = $this->getBuilder();
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
        $testNode = $nodeList[0];
        $vhttpequiv = $testNode->httpEquiv;
        $this->assertEqualsData('httpEquivLink', 'Content-Type', $vhttpequiv);
    }
}