<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/button04.js.
class Button04Test extends DomTestCase
{
    public function testButton04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'button04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $formNode = null;
        $vfmethod = null;
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
        $vfmethod = $formNode->method;
        $this->assertEqualsData('formLink', strtolower('POST'), strtolower($vfmethod));
    }
}