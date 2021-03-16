<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement08.js.
class HTMLInputElement08Test extends DomTestCase
{
    public function testHTMLInputElement08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vchecked = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList->item(2);
        $vchecked = $testNode->checked;
        $this->assertTrueData('checkedLink', $vchecked);
    }
}