<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement02.js.
class HTMLTableElement02Test extends W3CTestHarness
{
    public function testHTMLTableElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTableElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcaption = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->w3cAssertSize('Asize', 3, $nodeList);
        $testNode = $nodeList->item(0);
        $vcaption = $testNode->caption;
        $this->w3cAssertNull('captionLink', $vcaption);
    }
}
