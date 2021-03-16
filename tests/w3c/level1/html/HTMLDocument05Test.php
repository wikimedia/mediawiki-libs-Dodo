<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument05.js.
class HTMLDocument05Test extends DomTestCase
{
    public function testHTMLDocument05()
    {
        $builder = $this->getBuilder();
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