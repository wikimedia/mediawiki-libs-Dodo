<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/button07.js.
class Button07Test extends W3CTestHarness
{
    public function testButton07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'button07') != null) {
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
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->w3cAssertSize('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vtype = $testNode->type;
        $this->w3cAssertEquals('typeLink', 'reset', $vtype);
    }
}
