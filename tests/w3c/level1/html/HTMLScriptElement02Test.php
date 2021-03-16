<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement02.js.
class HTMLScriptElement02Test extends DomTestCase
{
    public function testHTMLScriptElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLScriptElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcharset = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcharset = $testNode->charset;
        $this->assertEqualsData('charsetLink', 'US-ASCII', $vcharset);
    }
}