<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementnormalize.js.
class HcElementnormalizeTest extends DomTestCase
{
    public function testHcElementnormalize()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_elementnormalize') != null) {
            return;
        }
        $doc = null;
        $root = null;
        $elementList = null;
        $testName = null;
        $firstChild = null;
        $childValue = null;
        $textNode = null;
        $retNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('sup');
        $testName = $elementList[0];
        $textNode = $doc->createTextNode('');
        $retNode = $testName->appendChild($textNode);
        $textNode = $doc->createTextNode(',000');
        $retNode = $testName->appendChild($textNode);
        $root = $doc->documentElement;
        $root->normalize();
        $elementList = $doc->getElementsByTagName('sup');
        $testName = $elementList[0];
        $firstChild = $testName->firstChild;
        $childValue = $firstChild->nodeValue;
        $this->assertEqualsData('elementNormalizeAssert', '56,000,000', $childValue);
    }
}