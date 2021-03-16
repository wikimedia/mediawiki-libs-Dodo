<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object07.js.
class Object07Test extends DomTestCase
{
    public function testObject07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object07') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vheight = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vheight = $testNode->height;
        $this->assertEqualsData('heightLink', '60', $vheight);
    }
}