<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement07.js.
class HTMLImageElement07Test extends DomTestCase
{
    public function testHTMLImageElement07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLImageElement07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vismap = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'img');
        $nodeList = $doc->getElementsByTagName('img');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vismap = $testNode->isMap;
        $this->assertFalseData('isMapLink', $vismap);
    }
}