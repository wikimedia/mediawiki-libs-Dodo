<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_characterdatareplacedataexceedslengthofdata.js.
class HcCharacterdatareplacedataexceedslengthofdataTest extends DomTestCase
{
    public function testHcCharacterdatareplacedataexceedslengthofdata()
    {
        $builder = $this->getBuilder();
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
        $nameNode = $elementList[0];
        $child = $nameNode->firstChild;
        $child->replaceData(0, 50, '2600');
        $childData = $child->data;
        $this->assertEqualsData('characterdataReplaceDataExceedsLengthOfDataAssert', '2600', $childData);
    }
}