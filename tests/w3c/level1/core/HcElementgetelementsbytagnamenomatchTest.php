<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagnamenomatch.js.
class HcElementgetelementsbytagnamenomatchTest extends DomTestCase
{
    public function testHcElementgetelementsbytagnamenomatch()
    {
        $builder = $this->getBuilder();
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