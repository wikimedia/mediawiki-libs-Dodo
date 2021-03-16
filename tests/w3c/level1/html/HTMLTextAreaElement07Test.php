<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement07.js.
class HTMLTextAreaElement07Test extends DomTestCase
{
    public function testHTMLTextAreaElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement07') != null) {
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
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vname = $testNode->name;
        $this->assertEqualsData('nameLink', 'text1', $vname);
    }
}