<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLinkElement03.js.
class HTMLLinkElement03Test extends W3CTestHarness
{
    public function testHTMLLinkElement03()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLLinkElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'link');
        $nodeList = $doc->getElementsByTagName('link');
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vhref = $testNode->href;
        $this->w3cAssertURIEquals('hrefLink', null, null, null, 'glossary.html', null, null, null, null, $vhref);
    }
}
