<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLIFrameElement07.js.
class HTMLIFrameElement07Test extends W3cTestHarness
{
    public function testHTMLIFrameElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLIFrameElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vname = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'iframe');
        $nodeList = $doc->getElementsByTagName('iframe');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vname = $testNode->name;
        $this->assertEqualsData('nameLink', 'Iframe1', $vname);
    }
}
