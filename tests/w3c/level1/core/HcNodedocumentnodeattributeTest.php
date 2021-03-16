<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodedocumentnodeattribute.js.
class HcNodedocumentnodeattributeTest extends DomTestCase
{
    public function testHcNodedocumentnodeattribute()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodedocumentnodeattribute') != null) {
            return;
        }
        $doc = null;
        $attrList = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $attrList = $doc->attributes;
        $this->assertNullData('doc_attributes_is_null', $attrList);
    }
}