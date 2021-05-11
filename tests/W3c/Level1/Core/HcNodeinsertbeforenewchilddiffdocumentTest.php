<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeinsertbeforenewchilddiffdocument.js.
class HcNodeinsertbeforenewchilddiffdocumentTest extends W3cTestHarness
{
    public function testHcNodeinsertbeforenewchilddiffdocument()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeinsertbeforenewchilddiffdocument') != null) {
            return;
        }
        $doc1 = null;
        $doc2 = null;
        $refChild = null;
        $newChild = null;
        $elementList = null;
        $elementNode = null;
        $insertedNode = null;
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
        $refChild = $elementNode->firstChild;
        $success = false;
        try {
            $insertedNode = $elementNode->insertBefore($newChild, $refChild);
        } catch (DomException $ex) {
            $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 4;
        }
        $this->assertTrueData('throw_WRONG_DOCUMENT_ERR', $success);
    }
}