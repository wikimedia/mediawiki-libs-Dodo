<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_noderemovechildoldchildnonexistent.js.
class HcNoderemovechildoldchildnonexistentTest extends DomTestCase
{
    public function testHcNoderemovechildoldchildnonexistent()
    {
        $builder = $this->getBuilder();
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