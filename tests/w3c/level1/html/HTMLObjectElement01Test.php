<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement01.js.
class HTMLObjectElement01Test extends DomTestCase
{
    public function testHTMLObjectElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLObjectElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $fNode = null;
        $vform = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object2');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->assertEqualsData('idLink', 'object2', $vform);
    }
}