<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement01.js.
class HTMLScriptElement01Test extends DomTestCase
{
    public function testHTMLScriptElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLScriptElement01') != null) {
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
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtext = $testNode->text;
        $this->assertEqualsData('textLink', 'var a=2;', $vtext);
    }
}