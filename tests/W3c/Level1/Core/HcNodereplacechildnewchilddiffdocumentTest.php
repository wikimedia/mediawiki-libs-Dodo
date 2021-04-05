<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodereplacechildnewchilddiffdocument.js.
class HcNodereplacechildnewchilddiffdocumentTest extends W3cTestHarness
{
    public function testHcNodereplacechildnewchilddiffdocument()
    {
        $builder = $this->getBuilder();
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
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 4;
        }
        $this->assertTrueData('throw_WRONG_DOCUMENT_ERR', $success);
    }
}
