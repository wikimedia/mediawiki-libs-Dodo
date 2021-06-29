<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildoldchildnonexistent.js.
class HcNoderemovechildoldchildnonexistentTest extends W3CTestHarness
{
    public function testHcNoderemovechildoldchildnonexistent()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_noderemovechildoldchildnonexistent') != null) {
            return;
        }
        $doc = null;
        $oldChild = null;
        $elementList = null;
        $elementNode = null;
        $removedChild = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $oldChild = $doc->createElement('br');
        $elementList = $doc->getElementsByTagName('p');
        $elementNode = $elementList->item(1);
        $success = false;
        try {
            $removedChild = $elementNode->removeChild($oldChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 8;
        }
        $this->assertTrueData('throw_NOT_FOUND_ERR', $success);
    }
}
