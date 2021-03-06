<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement04.js.
class HTMLFormElement04Test extends W3CTestHarness
{
    public function testHTMLFormElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLFormElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vacceptcharset = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vacceptcharset = $testNode->acceptCharset;
        $this->w3cAssertEquals('acceptCharsetLink', 'US-ASCII', $vacceptcharset);
    }
}
