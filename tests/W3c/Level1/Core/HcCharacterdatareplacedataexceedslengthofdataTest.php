<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedataexceedslengthofdata.js.
class HcCharacterdatareplacedataexceedslengthofdataTest extends W3cTestHarness
{
    public function testHcCharacterdatareplacedataexceedslengthofdata()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_characterdatareplacedataexceedslengthofdata') != null) {
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
        $child->replaceData(0, 50, '2600');
        $childData = $child->data;
        $this->assertEqualsData('characterdataReplaceDataExceedsLengthOfDataAssert', '2600', $childData);
    }
}