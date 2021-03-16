<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement06.js.
class HTMLAreaElement06Test extends DomTestCase
{
    public function testHTMLAreaElement06()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement06') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vshape = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vshape = $testNode->shape;
        $this->assertEqualsData('shapeLink', strtolower('rect'), strtolower($vshape));
    }
}