<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAnchorElement;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement11.js.
class HTMLAnchorElement11Test extends W3CTestHarness
{
    public function testHTMLAnchorElement11()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLAnchorElement11') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtarget = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor2');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vtarget = $testNode->target;
        $this->assertEqualsData('targetLink', 'dynamic', $vtarget);
    }
}
