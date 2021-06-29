<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement89.js.
class HTMLElement89Test extends W3CTestHarness
{
    public function testHTMLElement89()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLElement89') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdir = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('sub');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vdir = $testNode->dir;
        $this->assertEqualsData('dirLink', 'ltr', $vdir);
    }
}
