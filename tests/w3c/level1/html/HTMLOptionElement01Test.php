<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement01.js.
class HTMLOptionElement01Test extends DomTestCase
{
    public function testHTMLOptionElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptionElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vform = null;
        $fNode = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->assertSizeData('Asize', 10, $nodeList);
        $testNode = $nodeList[0];
        $fNode = $testNode->form;
        $vform = $fNode->id;
        $this->assertEqualsData('formLink', 'form1', $vform);
    }
}