<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_namednodemapnumberofnodes.js.
class HcNamednodemapnumberofnodesTest extends DomTestCase
{
    public function testHcNamednodemapnumberofnodes()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_namednodemapnumberofnodes') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testEmployee = null;
        $attributes = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $testEmployee = $elementList->item(2);
        $attributes = $testEmployee->attributes;
        $length = count($attributes);
        if ($builder->contentType == 'text/html') {
            $this->assertEqualsData('htmlLength', 2, $length);
        } else {
            $this->assertEqualsData('length', 3, $length);
        }
    }
}