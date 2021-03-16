<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement05.js.
class HTMLFormElement05Test extends DomTestCase
{
    public function testHTMLFormElement05()
    {
        $builder = $this->getBuilder();
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
        $testNode = $nodeList[0];
        $vaction = $testNode->action;
        $this->assertURIEqualsData('actionLink', null, null, null, 'getData.pl', null, null, null, null, $vaction);
    }
}