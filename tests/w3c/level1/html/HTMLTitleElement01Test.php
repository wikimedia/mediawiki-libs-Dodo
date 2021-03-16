<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTitleElement01.js.
class HTMLTitleElement01Test extends DomTestCase
{
    public function testHTMLTitleElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTitleElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtext = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'title');
        $nodeList = $doc->getElementsByTagName('title');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtext = $testNode->text;
        $this->assertEqualsData('textLink', 'NIST DOM HTML Test - TITLE', $vtext);
    }
}