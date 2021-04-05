<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement57.js.
class HTMLElement57Test extends W3cTestHarness
{
    public function testHTMLElement57()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement57') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtitle = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('address');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtitle = $testNode->title;
        $this->assertEqualsData('titleLink', 'ADDRESS Element', $vtitle);
    }
}
