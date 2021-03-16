<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement12.js.
class HTMLInputElement12Test extends DomTestCase
{
    public function testHTMLInputElement12()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement12') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vreadonly = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList[0];
        $vreadonly = $testNode->readOnly;
        $this->assertTrueData('readonlyLink', $vreadonly);
    }
}