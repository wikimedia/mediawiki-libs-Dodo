<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedataexceedslengthofarg.js.
class HcCharacterdatareplacedataexceedslengthofargTest extends W3CTestHarness
{
    public function testHcCharacterdatareplacedataexceedslengthofarg()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatareplacedataexceedslengthofarg') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $child = null;
        $childData = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('acronym');
        $nameNode = $elementList->item(0);
        $child = $nameNode->firstChild;
        $child->replaceData(0, 4, '260030');
        $childData = $child->data;
        $this->assertEqualsData('characterdataReplaceDataExceedsLengthOfArgAssert', '260030 North Ave. Dallas, Texas 98551', $childData);
    }
}