<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument16.js.
class HTMLDocument16Test extends DomTestCase
{
    public function testHTMLDocument16()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLDocument16') != null) {
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
        $elementNode = $doc->getElementById('noid');
        $this->assertNullData('elementId', $elementNode);
    }
}