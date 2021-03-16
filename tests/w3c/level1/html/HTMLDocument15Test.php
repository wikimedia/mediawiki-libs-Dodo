<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument15.js.
class HTMLDocument15Test extends DomTestCase
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