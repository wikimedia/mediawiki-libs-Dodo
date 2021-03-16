<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object12.js.
class Object12Test extends DomTestCase
{
    public function testObject12()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object12') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vusemap = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vusemap = $testNode->useMap;
        $this->assertEqualsData('useMapLink', '#DivLogo-map', $vusemap);
    }
}