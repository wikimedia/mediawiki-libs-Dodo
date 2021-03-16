<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/table01.js.
class Table01Test extends DomTestCase
{
    public function testTable01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'table01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcaption = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table1');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcaption = $testNode->caption;
        $this->assertNullData('captionLink', $vcaption);
    }
}