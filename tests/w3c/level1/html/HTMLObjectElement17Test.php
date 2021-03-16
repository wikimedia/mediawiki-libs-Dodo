<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement17.js.
class HTMLObjectElement17Test extends DomTestCase
{
    public function testHTMLObjectElement17()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLObjectElement17') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vwidth = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vwidth = $testNode->width;
        $this->assertEqualsData('widthLink', '550', $vwidth);
    }
}