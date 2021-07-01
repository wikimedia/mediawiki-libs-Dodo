<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildnewchilddiffdocument.js.
class HcNodereplacechildnewchilddiffdocumentTest extends W3CTestHarness
{
    public function testHcNodereplacechildnewchilddiffdocument()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodereplacechildnewchilddiffdocument') != null) {
            return;
        }
        $doc1 = null;
        $doc2 = null;
        $oldChild = null;
        $newChild = null;
        $elementList = null;
        $elementNode = null;
        $replacedChild = null;
        $doc1Ref = null;
        if (gettype($this->doc1) != NULL) {
            $doc1Ref = $this->doc1;
        }
        $doc1 = $this->load($doc1Ref, 'doc1', 'hc_staff');
        $doc2Ref = null;
        if (gettype($this->doc2) != NULL) {
            $doc2Ref = $this->doc2;
        }
        $doc2 = $this->load($doc2Ref, 'doc2', 'hc_staff');
        $newChild = $doc1->createElement('br');
        $elementList = $doc2->getElementsByTagName('p');
        $elementNode = $elementList->item(1);
        $oldChild = $elementNode->firstChild;
        $success = false;
        try {
            $replacedChild = $elementNode->replaceChild($newChild, $oldChild);
        } catch (DOMException $ex) {
            $success = gettype($ex->code) != NULL && $ex->code == 4;
        }
        $this->w3cAssertTrue('throw_WRONG_DOCUMENT_ERR', $success);
    }
}
