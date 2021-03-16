<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexnotzero.js.
class HcNodelistindexnotzeroTest extends DomTestCase
{
    public function testHcNodelistindexnotzero()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodelistindexnotzero') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $employeeList = null;
        $child = null;
        $childName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(2);
        $employeeList = $employeeNode->childNodes;
        $child = $employeeList->item(3);
        $childName = $child->nodeName;
        if ('#text' == $childName) {
            $this->assertEqualsData('childName_space', '#text', $childName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'childName_strong', 'strong', $childName);
        }
    }
}