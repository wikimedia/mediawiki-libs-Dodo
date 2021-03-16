<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLButtonElement02.js.
class HTMLButtonElement02Test extends DomTestCase
{
    public function testHTMLButtonElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLButtonElement02') != null) {
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
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(1);
        $vform = $testNode->form;
        $this->assertNullData('formNullLink', $vform);
    }
}