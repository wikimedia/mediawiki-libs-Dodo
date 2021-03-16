<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument17.js.
class HTMLDocument17Test extends DomTestCase
{
    public function testHTMLDocument17()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLDocument17') != null) {
            return;
        }
        $doc = null;
        $bodyElem = null;
        $bodyChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        $doc->open();
        $doc->close();
        $bodyElem = $doc->body;
        if ($bodyElem != null) {
            $bodyChild = $bodyElem->firstChild;
            $this->assertNullData('bodyContainsChildren', $bodyChild);
        }
    }
}