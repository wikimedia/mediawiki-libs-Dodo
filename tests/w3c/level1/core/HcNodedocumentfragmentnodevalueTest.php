<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentfragmentnodevalue.js.
class HcNodedocumentfragmentnodevalueTest extends DomTestCase
{
    public function testHcNodedocumentfragmentnodevalue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentfragmentnodevalue') != null) {
            return;
        }
        $doc = null;
        $docFragment = null;
        $attrList = null;
        $value = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docFragment = $doc->createDocumentFragment();
        $attrList = $docFragment->attributes;
        $this->assertNullData('attributesNull', $attrList);
        $value = $docFragment->nodeValue;
        $this->assertNullData('initiallyNull', $value);
    }
}