<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeclonenodetrue.js.
class HcNodeclonenodetrueTest extends W3cTestHarness
{
    public function testHcNodeclonenodetrue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
