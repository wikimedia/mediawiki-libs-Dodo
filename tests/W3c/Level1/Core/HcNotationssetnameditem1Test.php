<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_notationssetnameditem1.js.
class HcNotationssetnameditem1Test extends W3cTestHarness
{
    public function testHcNotationssetnameditem1()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_notationssetnameditem1') != null) {
            return;
        }
        $doc = null;
        $notations = null;
        $docType = null;
        $retval = null;
        $elem = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->assertNotNullData('docTypeNotNull', $docType);
            $notations = $docType->notations;
            $this->assertNotNullData('notationsNotNull', $notations);
            $elem = $doc->createElement('br');
            try {
                $retval = $notations->setNamedItem($elem);
                $this->makeFailed('throw_HIER_OR_NO_MOD_ERR');
            } catch (DomException $ex) {
                if (gettype($ex->getCode()) != NULL) {
                    switch ($ex->getCode()) {
                        case 3:
                            break;
                        case 7:
                            break;
                        default:
                            $this->fail($ex->getMessage());
                    }
                } else {
                    $this->fail($ex->getMessage());
                }
            }
        }
    }
}
