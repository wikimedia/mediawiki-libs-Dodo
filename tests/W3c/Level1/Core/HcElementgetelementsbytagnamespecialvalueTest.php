<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgetelementsbytagnamespecialvalue.js.
class HcElementgetelementsbytagnamespecialvalueTest extends W3cTestHarness
{
    public function testHcElementgetelementsbytagnamespecialvalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementgetelementsbytagnamespecialvalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $lastEmployee = null;
        $lastempList = null;
        $child = null;
        $childName = null;
        $result = [];
        $expectedResult = [];
        $expectedResult[0] = 'em';
        $expectedResult[1] = 'strong';
        $expectedResult[2] = 'code';
        $expectedResult[3] = 'sup';
        $expectedResult[4] = 'var';
        $expectedResult[5] = 'acronym';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $lastEmployee = $elementList->item(4);
        $lastempList = $lastEmployee->getElementsByTagName('*');
        for ($indexN10067 = 0; $indexN10067 < count($lastempList); $indexN10067++) {
            $child = $lastempList->item($indexN10067);
            $childName = $child->nodeName;
            $result[count($result)] = $childName;
        }
        $this->assertEqualsListAutoCaseData('element', 'tagNames', $expectedResult, $result);
    }
}
