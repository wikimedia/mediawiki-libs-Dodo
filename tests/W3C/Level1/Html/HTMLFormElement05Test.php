<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement05.js.
class HTMLFormElement05Test extends W3CTestHarness
{
    public function testHTMLFormElement05()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLFormElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaction = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vaction = $testNode->action;
        $this->assertURIEqualsData('actionLink', null, null, null, 'getData.pl', null, null, null, null, $vaction);
    }
}
