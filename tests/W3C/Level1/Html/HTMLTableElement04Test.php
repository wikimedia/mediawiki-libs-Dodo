<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement04.js.
class HTMLTableElement04Test extends W3CTestHarness
{
    public function testHTMLTableElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertSize('Asize', 3, $nodeList);
        $testNode = $nodeList->item(0);
        $vsection = $testNode->tHead;
        $this->w3cAssertNull('sectionLink', $vsection);
    }
}
