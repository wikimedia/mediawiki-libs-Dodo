<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement08.js.
class HTMLElement08Test extends W3CTestHarness
{
    public function testHTMLElement08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLElement08') != null) {
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
        $nodeList = $doc->getElementsByTagName('b');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vid = $testNode->id;
        $this->assertEqualsData('idLink', 'Test-B', $vid);
    }
}
