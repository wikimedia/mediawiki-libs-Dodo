<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement98.js.
class HTMLElement98Test extends DomTestCase
{
    public function testHTMLElement98()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement98') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdir = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('strike');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdir = $testNode->dir;
        $this->assertEqualsData('dirLink', 'ltr', $vdir);
    }
}