<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement80.js.
class HTMLElement80Test extends W3cTestHarness
{
    public function testHTMLElement80()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement80') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlang = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('acronym');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vlang = $testNode->lang;
        $this->assertEqualsData('langLink', 'en', $vlang);
    }
}
