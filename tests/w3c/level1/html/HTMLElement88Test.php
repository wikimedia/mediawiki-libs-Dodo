<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement88.js.
class HTMLElement88Test extends DomTestCase
{
    public function testHTMLElement88()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement88') != null) {
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
        $nodeList = $doc->getElementsByTagName('head');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdir = $testNode->dir;
        $this->assertEqualsData('dirLink', 'ltr', $vdir);
    }
}