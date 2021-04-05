<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLinkElement03.js.
class HTMLLinkElement03Test extends W3cTestHarness
{
    public function testHTMLLinkElement03()
    {
        $builder = $this->getBuilder();
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
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vhref = $testNode->href;
        $this->assertURIEqualsData('hrefLink', null, null, null, 'glossary.html', null, null, null, null, $vhref);
    }
}
