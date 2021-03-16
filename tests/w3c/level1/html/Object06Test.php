<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object06.js.
class Object06Test extends DomTestCase
{
    public function testObject06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdata = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vdata = $testNode->data;
        $vdata = $testNode->getAttribute('data');
        //CSA hack
        $this->assertEqualsData('dataLink', './pix/logo.gif', $vdata);
    }
}