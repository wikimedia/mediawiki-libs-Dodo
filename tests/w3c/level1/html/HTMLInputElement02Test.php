<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement02.js.
class HTMLInputElement02Test extends DomTestCase
{
    public function testHTMLInputElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdefaultchecked = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList->item(3);
        $vdefaultchecked = $testNode->defaultChecked;
        $this->assertTrueData('defaultCheckedLink', $vdefaultchecked);
    }
}