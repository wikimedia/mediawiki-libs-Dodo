<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/button05.js.
class Button05Test extends DomTestCase
{
    public function testButton05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'button05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vakey = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vakey = $testNode->accessKey;
        $this->assertEqualsData('accessKeyLink', strtolower('f'), strtolower($vakey));
    }
}