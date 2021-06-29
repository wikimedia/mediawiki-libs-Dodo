<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement21.js.
class HTMLInputElement21Test extends W3CTestHarness
{
    public function testHTMLInputElement21()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLInputElement21') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $doc = null;
        $checked = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList->item(1);
        $checked = $testNode->checked;
        $this->assertFalseData('notCheckedBeforeClick', $checked);
        $testNode->click();
        $checked = $testNode->checked;
        $this->assertTrueData('checkedAfterClick', $checked);
    }
}
