<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument05.js.
class HTMLDocument05Test extends W3CTestHarness
{
    public function testHTMLDocument05()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLDocument05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vbody = null;
        $vid = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        $vbody = $doc->body;
        $vid = $vbody->id;
        $this->assertEqualsData('idLink', 'TEST-BODY', $vid);
    }
}
