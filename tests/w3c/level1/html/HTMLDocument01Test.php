<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument01.js.
class HTMLDocument01Test extends DomTestCase
{
    public function testHTMLDocument01()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('titleLink', 'NIST DOM HTML Test - DOCUMENT', $vtitle);
    }
}