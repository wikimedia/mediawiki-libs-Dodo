<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement19.js.
class HTMLObjectElement19Test extends W3cTestHarness
{
    public function testHTMLObjectElement19()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLObjectElement19') != null) {
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
        $doc = $this->load($docRef, 'doc', 'object2');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList->item(0);
        $vform = $testNode->form;
        $this->assertNullData('formNullLink', $vform);
    }
}
