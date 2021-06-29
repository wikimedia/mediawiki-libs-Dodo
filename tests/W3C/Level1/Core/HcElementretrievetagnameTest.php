<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementretrievetagname.js.
class HcElementretrievetagnameTest extends W3CTestHarness
{
    public function testHcElementretrievetagname()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementretrievetagname') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $testEmployee = null;
        $strong = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('code');
        $testEmployee = $elementList->item(1);
        $strong = $testEmployee->nodeName;
        $this->assertEqualsAutoCaseData('element', 'nodename', 'code', $strong);
        $strong = $testEmployee->tagName;
        $this->assertEqualsAutoCaseData('element', 'tagname', 'code', $strong);
    }
}
