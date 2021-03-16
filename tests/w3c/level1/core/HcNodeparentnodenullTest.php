<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeparentnodenull.js.
class HcNodeparentnodenullTest extends DomTestCase
{
    public function testHcNodeparentnodenull()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeparentnodenull') != null) {
            return;
        }
        $doc = null;
        $createdNode = null;
        $parentNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $createdNode = $doc->createElement('br');
        $parentNode = $createdNode->parentNode;
        $this->assertNullData('parentNode', $parentNode);
    }
}