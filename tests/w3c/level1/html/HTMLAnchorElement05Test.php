<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement05.js.
class HTMLAnchorElement05Test extends DomTestCase
{
    public function testHTMLAnchorElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAnchorElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhreflink = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vhreflink = $testNode->hreflang;
        $this->assertEqualsData('hreflangLink', 'en', $vhreflink);
    }
}