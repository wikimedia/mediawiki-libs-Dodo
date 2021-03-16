<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodevalue.js.
class HcNodedocumentnodevalueTest extends DomTestCase
{
    public function testHcNodedocumentnodevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodevalue') != null) {
            return;
        }
        $doc = null;
        $documentValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $documentValue = $doc->nodeValue;
        $this->assertNullData('documentNodeValue', $documentValue);
    }
}