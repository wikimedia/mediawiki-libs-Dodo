<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/anchor01.js.
class Anchor01Test extends DomTestCase
{
    public function testAnchor01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'anchor01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaccesskey = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vaccesskey = $testNode->accessKey;
        $this->assertEqualsData('accessKeyLink', 'g', $vaccesskey);
    }
}