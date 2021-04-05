<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument15.js.
class HTMLDocument15Test extends W3cTestHarness
{
    public function testHTMLDocument15()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLDocument15') != null) {
            return;
        }
        $elementNode = null;
        $elementValue = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        $elementNode = $doc->getElementById('mapid');
        $elementValue = $elementNode->nodeName;
        $this->assertEqualsAutoCaseData('element', 'elementId', 'map', $elementValue);
    }
}
