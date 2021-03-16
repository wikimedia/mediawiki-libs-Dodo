<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOListElement02.js.
class HTMLOListElement02Test extends DomTestCase
{
    public function testHTMLOListElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOListElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vstart = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'olist');
        $nodeList = $doc->getElementsByTagName('ol');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vstart = $testNode->start;
        $this->assertEqualsData('startLink', 1, $vstart);
    }
}