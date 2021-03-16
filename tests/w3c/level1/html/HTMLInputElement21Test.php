<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement21.js.
class HTMLInputElement21Test extends DomTestCase
{
    public function testHTMLInputElement21()
    {
        $builder = $this->getBuilder();
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