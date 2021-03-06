<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement04.js.
class HTMLInputElement04Test extends W3CTestHarness
{
    public function testHTMLInputElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLInputElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaccept = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->w3cAssertSize('Asize', 9, $nodeList);
        $testNode = $nodeList->item(8);
        $vaccept = $testNode->accept;
        $this->w3cAssertEquals('acceptLink', 'GIF,JPEG', $vaccept);
    }
}
