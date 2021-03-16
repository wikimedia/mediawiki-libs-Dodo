<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLObjectElement10.js.
class HTMLObjectElement10Test extends DomTestCase
{
    public function testHTMLObjectElement10()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLObjectElement10') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vheight = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'object');
        $nodeList = $doc->getElementsByTagName('object');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vheight = $testNode->height;
        $this->assertEqualsData('heightLink', '60', $vheight);
    }
}