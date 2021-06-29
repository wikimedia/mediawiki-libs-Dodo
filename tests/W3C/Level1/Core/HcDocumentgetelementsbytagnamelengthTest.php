<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetelementsbytagnamelength.js.
class HcDocumentgetelementsbytagnamelengthTest extends W3CTestHarness
{
    public function testHcDocumentgetelementsbytagnamelength()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentgetelementsbytagnamelength') != null) {
            return;
        }
        $doc = null;
        $nameList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $nameList = $doc->getElementsByTagName('strong');
        $this->assertSizeData('documentGetElementsByTagNameLengthAssert', 5, $nameList);
    }
}
