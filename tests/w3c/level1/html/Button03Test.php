<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/button03.js.
class Button03Test extends DomTestCase
{
    public function testButton03()
    {
        $builder = $this->getBuilder();
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
        $testNode = $nodeList[0];
        $formNode = $testNode->form;
        $vfaction = $formNode->action;
        $this->assertEqualsData('formLink', '...', $vfaction);
    }
}