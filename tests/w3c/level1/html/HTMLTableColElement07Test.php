<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableColElement07.js.
class HTMLTableColElement07Test extends DomTestCase
{
    public function testHTMLTableColElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableColElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vspan = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'tablecol');
        $nodeList = $doc->getElementsByTagName('col');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vspan = $testNode->span;
        $this->assertEqualsData('spanLink', 1, $vspan);
    }
}