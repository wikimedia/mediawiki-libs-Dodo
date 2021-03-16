<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagname.js.
class HcElementgetelementsbytagnameTest extends DomTestCase
{
    public function testHcElementgetelementsbytagname()
    {
        $builder = $this->getBuilder();
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