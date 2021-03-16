<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodelistindexgetlengthofemptylist.js.
class HcNodelistindexgetlengthofemptylistTest extends DomTestCase
{
    public function testHcNodelistindexgetlengthofemptylist()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodelistindexgetlengthofemptylist') != null) {
            return;
        }
        $doc = null;
        $emList = null;
        $emNode = null;
        $textNode = null;
        $textList = null;
        $length = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $emList = $doc->getElementsByTagName('em');
        $emNode = $emList->item(2);
        $textNode = $emNode->firstChild;
        $textList = $textNode->childNodes;
        $length = count($textList);
        $this->assertEqualsData('length', 0, $length);
    }
}