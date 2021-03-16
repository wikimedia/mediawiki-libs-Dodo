<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetlastchildnull.js.
class HcNodegetlastchildnullTest extends DomTestCase
{
    public function testHcNodegetlastchildnull()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodegetlastchildnull') != null) {
            return;
        }
        $doc = null;
        $emList = null;
        $emNode = null;
        $emText = null;
        $nullChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $emList = $doc->getElementsByTagName('em');
        $emNode = $emList[0];
        $emText = $emNode->firstChild;
        $nullChild = $emText->lastChild;
        $this->assertNullData('nullChild', $nullChild);
    }
}