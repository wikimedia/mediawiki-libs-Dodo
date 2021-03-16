<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_namednodemapchildnoderange.js.
class HcNamednodemapchildnoderangeTest extends DomTestCase
{
    public function testHcNamednodemapchildnoderange()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_namednodemapchildnoderange') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testEmployee = null;
        $attributes = null;
        $child = null;
        $strong = null;
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
            $child = $attributes->item(2);
            $this->assertNotNullData('attr2', $child);
        }
        $child = $attributes[0];
        $this->assertNotNullData('attr0', $child);
        $child = $attributes->item(1);
        $this->assertNotNullData('attr1', $child);
        $child = $attributes->item(3);
        $this->assertNullData('attr3', $child);
    }
}