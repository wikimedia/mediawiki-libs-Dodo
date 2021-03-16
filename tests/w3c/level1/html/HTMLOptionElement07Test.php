<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptionElement07.js.
class HTMLOptionElement07Test extends DomTestCase
{
    public function testHTMLOptionElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptionElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlabel = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'option');
        $nodeList = $doc->getElementsByTagName('option');
        $this->assertSizeData('Asize', 10, $nodeList);
        $testNode = $nodeList->item(1);
        $vlabel = $testNode->label;
        $this->assertEqualsData('labelLink', 'l1', $vlabel);
    }
}