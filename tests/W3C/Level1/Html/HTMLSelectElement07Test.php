<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement07.js.
class HTMLSelectElement07Test extends W3CTestHarness
{
    public function testHTMLSelectElement07()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLSelectElement07') != null) {
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
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $vform = $testNode->form;
        $this->assertNullData('formNullLink', $vform);
    }
}
