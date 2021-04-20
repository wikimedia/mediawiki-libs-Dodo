<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement11.js.
class HTMLInputElement11Test extends DomTestCase
{
    public function testHTMLInputElement11()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement11') != null) {
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
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList[0];
        $vname = $testNode->name;
        $this->assertEqualsData('nameLink', 'Password', $vname);
    }
}