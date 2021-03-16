<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement08.js.
class HTMLObjectElement08Test extends DomTestCase
{
    public function testHTMLObjectElement08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLObjectElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdata = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vdata = $testNode->data;
        $this->assertURIEqualsData('dataLink', null, null, null, 'logo.gif', null, null, null, null, $vdata);
    }
}