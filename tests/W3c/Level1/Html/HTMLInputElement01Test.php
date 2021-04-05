<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement01.js.
class HTMLInputElement01Test extends W3cTestHarness
{
    public function testHTMLInputElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdefaultvalue = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList[0];
        $vdefaultvalue = $testNode->defaultValue;
        $this->assertEqualsData('defaultValueLink', 'Password', $vdefaultvalue);
    }
}
