<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/button03.js.
class Button03Test extends W3CTestHarness
{
    public function testButton03()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'button03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $formNode = null;
        $vfaction = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $formNode = $testNode->form;
        $vfaction = $formNode->action;
        $this->assertEqualsData('formLink', '...', $vfaction);
    }
}
