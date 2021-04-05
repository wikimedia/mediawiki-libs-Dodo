<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLQuoteElement01.js.
class HTMLQuoteElement01Test extends W3cTestHarness
{
    public function testHTMLQuoteElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLQuoteElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcite = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'quote');
        $nodeList = $doc->getElementsByTagName('q');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcite = $testNode->cite;
        $this->assertURIEqualsData('citeLink', null, null, null, 'Q.html', null, null, null, null, $vcite);
    }
}
