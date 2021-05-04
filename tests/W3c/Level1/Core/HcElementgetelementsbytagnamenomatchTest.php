<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagnamenomatch.js.
class HcElementgetelementsbytagnamenomatchTest extends W3cTestHarness
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
        $this->assertSizeData('elementGetElementsByTagNameNoMatchNoMatchAssert', 0, $elementList);
    }
}
