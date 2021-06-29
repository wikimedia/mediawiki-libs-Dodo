<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLParamElement02.js.
class HTMLParamElement02Test extends W3CTestHarness
{
    public function testHTMLParamElement02()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLParamElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vvalue = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'param');
        $nodeList = $doc->getElementsByTagName('param');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vvalue = $testNode->value;
        $this->assertURIEqualsData('valueLink', null, null, null, 'file.gif', null, null, null, null, $vvalue);
    }
}
