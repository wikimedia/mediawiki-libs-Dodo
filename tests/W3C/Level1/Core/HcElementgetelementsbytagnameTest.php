<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagname.js.
class HcElementgetelementsbytagnameTest extends W3CTestHarness
{
    public function testHcElementgetelementsbytagname()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementgetelementsbytagname') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $this->assertSizeData('elementGetElementsByTagNameAssert', 5, $elementList);
    }
}
