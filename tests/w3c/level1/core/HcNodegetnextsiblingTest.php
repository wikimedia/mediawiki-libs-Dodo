<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetnextsibling.js.
class HcNodegetnextsiblingTest extends DomTestCase
{
    public function testHcNodegetnextsibling()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodegetnextsibling') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $emNode = null;
        $nsNode = null;
        $nsName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('em');
        $emNode = $elementList->item(1);
        $nsNode = $emNode->nextSibling;
        $nsName = $nsNode->nodeName;
        $this->assertEqualsData('whitespace', '#text', $nsName);
    }
}