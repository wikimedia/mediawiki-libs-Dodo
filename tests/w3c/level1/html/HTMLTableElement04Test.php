<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement04.js.
class HTMLTableElement04Test extends DomTestCase
{
    public function testHTMLTableElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsection = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vsection = $testNode->tHead;
        $this->assertNullData('sectionLink', $vsection);
    }
}