<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetlastchildnull.js.
class HcNodegetlastchildnullTest extends W3CTestHarness
{
    public function testHcNodegetlastchildnull()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $emNode = $emList->item(0);
        $emText = $emNode->firstChild;
        $nullChild = $emText->lastChild;
        $this->assertNullData('nullChild', $nullChild);
    }
}
