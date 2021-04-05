<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement03.js.
class HTMLOptionElement03Test extends W3cTestHarness
{
    public function testHTMLOptionElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptionElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdefaultselected = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->assertSizeData('Asize', 10, $nodeList);
        $testNode = $nodeList[0];
        $vdefaultselected = $testNode->defaultSelected;
        $this->assertTrueData('defaultSelectedLink', $vdefaultselected);
    }
}
