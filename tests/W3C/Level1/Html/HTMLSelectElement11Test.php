<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement11.js.
class HTMLSelectElement11Test extends W3CTestHarness
{
    public function testHTMLSelectElement11()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLSelectElement11') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vname = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(0);
        $vname = $testNode->name;
        $this->assertEqualsData('nameLink', 'select1', $vname);
    }
}
