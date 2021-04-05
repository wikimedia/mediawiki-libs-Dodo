<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/button02.js.
class Button02Test extends W3cTestHarness
{
    public function testButton02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'button02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $formNode = null;
        $vfname = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $formNode = $testNode->form;
        $vfname = $formNode->id;
        $this->assertEqualsData('formLink', 'form2', $vfname);
    }
}
