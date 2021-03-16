<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonenodetrue.js.
class HcNodeclonenodetrueTest extends DomTestCase
{
    public function testHcNodeclonenodetrue()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeclonenodetrue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $employeeNode = null;
        $clonedNode = null;
        $clonedList = null;
        $clonedChild = null;
        $clonedChildName = null;
        $origList = null;
        $origChild = null;
        $origChildName = null;
        $result = [];
        $expected = [];
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $employeeNode = $elementList->item(1);
        $origList = $employeeNode->childNodes;
        for ($indexN10065 = 0; $indexN10065 < count($origList); $indexN10065++) {
            $origChild = $origList->item($indexN10065);
            $origChildName = $origChild->nodeName;
            $expected[count($expected)] = $origChildName;
        }
        $clonedNode = $employeeNode->cloneNode(true);
        $clonedList = $clonedNode->childNodes;
        for ($indexN1007B = 0; $indexN1007B < count($clonedList); $indexN1007B++) {
            $clonedChild = $clonedList->item($indexN1007B);
            $clonedChildName = $clonedChild->nodeName;
            $result[count($result)] = $clonedChildName;
        }
        $this->assertEqualsListData('clone', $expected, $result);
    }
}