<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLinkElement04.js.
class HTMLLinkElement04Test extends DomTestCase
{
    public function testHTMLLinkElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLLinkElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhreflang = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'link');
        $nodeList = $doc->getElementsByTagName('link');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vhreflang = $testNode->hreflang;
        $this->assertEqualsData('hreflangLink', 'en', $vhreflang);
    }
}