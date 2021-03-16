<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement05.js.
class HTMLAreaElement05Test extends DomTestCase
{
    public function testHTMLAreaElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vnohref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vnohref = $testNode->noHref;
        $this->assertFalseData('noHrefLink', $vnohref);
    }
}