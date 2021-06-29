<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildoldchildnonexistent.js.
class HcNodereplacechildoldchildnonexistentTest extends W3CTestHarness
{
    public function testHcNodereplacechildoldchildnonexistent()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodereplacechildoldchildnonexistent') != null) {
            return;
        }
        $doc = null;
        $oldChild = null;
        $newChild = null;
        $elementList = null;
        $elementNode = null;
        $replacedNode = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newChild = $doc->createElement('br');
        $oldChild = $doc->createElement('b');
        $elementList = $doc->getElementsByTagName('p');
        $elementNode = $elementList->item(1);
        $success = false;
        try {
            $replacedNode = $elementNode->replaceChild($newChild, $oldChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 8;
        }
        $this->assertTrueData('throw_NOT_FOUND_ERR', $success);
    }
}
