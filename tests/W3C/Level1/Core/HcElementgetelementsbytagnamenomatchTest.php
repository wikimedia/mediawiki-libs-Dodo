<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagnamenomatch.js.
class HcElementgetelementsbytagnamenomatchTest extends W3CTestHarness
{
    public function testHcElementgetelementsbytagnamenomatch()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementgetelementsbytagnamenomatch') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('noMatch');
        $this->w3cAssertSize('elementGetElementsByTagNameNoMatchNoMatchAssert', 0, $elementList);
    }
}
