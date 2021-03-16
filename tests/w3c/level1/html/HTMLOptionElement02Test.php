<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement02.js.
class HTMLOptionElement02Test extends DomTestCase
{
    public function testHTMLOptionElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptionElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vform = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->assertSizeData('Asize', 10, $nodeList);
        $testNode = $nodeList->item(6);
        $vform = $testNode->form;
        $this->assertNullData('formNullLink', $vform);
    }
}