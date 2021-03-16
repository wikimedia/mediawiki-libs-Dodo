<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object11.js.
class Object11Test extends DomTestCase
{
    public function testObject11()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object11') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtype = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vtype = $testNode->type;
        $this->assertEqualsData('typeLink', 'image/gif', $vtype);
    }
}