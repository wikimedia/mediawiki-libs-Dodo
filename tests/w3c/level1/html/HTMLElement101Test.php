<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement101.js.
class HTMLElement101Test extends DomTestCase
{
    public function testHTMLElement101()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement101') != null) {
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
        $nodeList = $doc->getElementsByTagName('em');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdir = $testNode->dir;
        $this->assertEqualsData('dirLink', 'ltr', $vdir);
    }
}