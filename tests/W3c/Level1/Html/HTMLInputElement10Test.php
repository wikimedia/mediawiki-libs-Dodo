<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement10.js.
class HTMLInputElement10Test extends W3cTestHarness
{
    public function testHTMLInputElement10()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement10') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmaxlength = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList[0];
        $vmaxlength = $testNode->maxLength;
        $this->assertEqualsData('maxlengthLink', 5, $vmaxlength);
    }
}
