<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/button09.js.
class Button09Test extends DomTestCase
{
    public function testButton09()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'button09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vvalue = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vvalue = $testNode->value;
        $this->assertEqualsData('typeLink', 'Reset Disabled Button', $vvalue);
    }
}