<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement24.js.
class HTMLElement24Test extends W3cTestHarness
{
    public function testHTMLElement24()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement24') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vid = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('dd');
        $this->assertSizeData('Asize', 4, $nodeList);
        $testNode = $nodeList[0];
        $vid = $testNode->id;
        $this->assertEqualsData('idLink', 'Test-DD', $vid);
    }
}
