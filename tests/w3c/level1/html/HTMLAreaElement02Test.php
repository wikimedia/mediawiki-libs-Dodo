<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement02.js.
class HTMLAreaElement02Test extends DomTestCase
{
    public function testHTMLAreaElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $valt = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $valt = $testNode->alt;
        $this->assertEqualsData('altLink', 'Domain', $valt);
    }
}