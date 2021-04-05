<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforerefchildnonexistent.js.
class HcNodeinsertbeforerefchildnonexistentTest extends W3cTestHarness
{
    public function testHcNodeinsertbeforerefchildnonexistent()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforerefchildnonexistent') != null) {
            return;
        }
        $doc = null;
        $refChild = null;
        $newChild = null;
        $elementList = null;
        $elementNode = null;
        $insertedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newChild = $doc->createElement('br');
        $refChild = $doc->createElement('b');
        $elementList = $doc->getElementsByTagName('p');
        $elementNode = $elementList->item(1);
        $success = false;
        try {
            $insertedNode = $elementNode->insertBefore($newChild, $refChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 8;
        }
        $this->assertTrueData('throw_NOT_FOUND_ERR', $success);
    }
}
