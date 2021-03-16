<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementretrievetagname.js.
class HcElementretrievetagnameTest extends DomTestCase
{
    public function testHcElementretrievetagname()
    {
        $builder = $this->getBuilder();
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