<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetelementsbytagnamelength.js.
class HcDocumentgetelementsbytagnamelengthTest extends DomTestCase
{
    public function testHcDocumentgetelementsbytagnamelength()
    {
        $builder = $this->getBuilder();
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