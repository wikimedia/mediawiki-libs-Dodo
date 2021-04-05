<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement04.js.
class HTMLElement04Test extends W3cTestHarness
{
    public function testHTMLElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement04') != null) {
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
        $nodeList = $doc->getElementsByTagName('span');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vid = $testNode->id;
        $this->assertEqualsData('idLink', 'Test-SPAN', $vid);
    }
}
