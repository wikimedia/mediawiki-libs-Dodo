<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument01.js.
class HTMLDocument01Test extends W3CTestHarness
{
    public function testHTMLDocument01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLDocument01') != null) {
            return;
        }
        $nodeList = null;
        $vtitle = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        $vtitle = $doc->title;
        $this->w3cAssertEquals('titleLink', 'NIST DOM HTML Test - DOCUMENT', $vtitle);
    }
}
