<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement05.js.
class HTMLScriptElement05Test extends DomTestCase
{
    public function testHTMLScriptElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLScriptElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtype = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtype = $testNode->type;
        $this->assertEqualsData('typeLink', 'text/javaScript', $vtype);
    }
}