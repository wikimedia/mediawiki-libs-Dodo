<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOptGroupElement02.js.
class HTMLOptGroupElement02Test extends DomTestCase
{
    public function testHTMLOptGroupElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOptGroupElement02') != null) {
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
        $doc = $this->load($docRef, 'doc', 'optgroup');
        $nodeList = $doc->getElementsByTagName('optgroup');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vlabel = $testNode->label;
        $this->assertEqualsData('labelLink', 'Regular Employees', $vlabel);
    }
}