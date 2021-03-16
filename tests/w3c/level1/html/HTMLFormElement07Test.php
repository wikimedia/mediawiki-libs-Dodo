<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement07.js.
class HTMLFormElement07Test extends DomTestCase
{
    public function testHTMLFormElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmethod = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vmethod = $testNode->method;
        $this->assertEqualsData('methodLink', 'post', $vmethod);
    }
}