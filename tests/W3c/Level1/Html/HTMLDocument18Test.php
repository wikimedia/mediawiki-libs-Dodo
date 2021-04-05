<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument18.js.
class HTMLDocument18Test extends W3cTestHarness
{
    public function testHTMLDocument18()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLDocument18') != null) {
            return;
        }
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        $doc->close();
    }
}
