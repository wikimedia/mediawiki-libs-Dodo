<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement03.js.
class HTMLFormElement03Test extends W3cTestHarness
{
    public function testHTMLFormElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement03') != null) {
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
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vname = $testNode->id;
        $this->assertEqualsData('nameLink', 'form1', $vname);
    }
}
