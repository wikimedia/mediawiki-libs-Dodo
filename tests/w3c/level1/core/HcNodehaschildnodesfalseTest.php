<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodehaschildnodesfalse.js.
class HcNodehaschildnodesfalseTest extends DomTestCase
{
    public function testHcNodehaschildnodesfalse()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodehaschildnodesfalse') != null) {
            return;
        }
        $doc = null;
        $emList = null;
        $emNode = null;
        $emText = null;
        $hasChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $emList = $doc->getElementsByTagName('em');
        $emNode = $emList[0];
        $emText = $emNode->firstChild;
        $hasChild = $emText->hasChildNodes();
        $this->assertFalseData('hasChild', $hasChild);
    }
}