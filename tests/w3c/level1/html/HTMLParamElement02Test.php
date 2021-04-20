<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLParamElement02.js.
class HTMLParamElement02Test extends DomTestCase
{
    public function testHTMLParamElement02()
    {
        $builder = $this->getBuilder();
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
        $testNode = $nodeList[0];
        $vvalue = $testNode->value;
        $this->assertURIEqualsData('valueLink', null, null, null, 'file.gif', null, null, null, null, $vvalue);
    }
}