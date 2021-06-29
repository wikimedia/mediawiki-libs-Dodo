<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_namednodemapnumberofnodes.js.
class HcNamednodemapnumberofnodesTest extends W3CTestHarness
{
    public function testHcNamednodemapnumberofnodes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
