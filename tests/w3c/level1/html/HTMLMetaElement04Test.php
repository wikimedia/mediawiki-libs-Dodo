<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLMetaElement04.js.
class HTMLMetaElement04Test extends DomTestCase
{
    public function testHTMLMetaElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLMetaElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vscheme = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'meta');
        $nodeList = $doc->getElementsByTagName('meta');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vscheme = $testNode->scheme;
        $this->assertEqualsData('schemeLink', 'NIST', $vscheme);
    }
}