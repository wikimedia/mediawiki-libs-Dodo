<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/object10.js.
class Object10Test extends DomTestCase
{
    public function testObject10()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'object10') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtabindex = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vtabindex = $testNode->tabIndex;
        $this->assertEqualsData('tabIndexLink', 0, $vtabindex);
    }
}