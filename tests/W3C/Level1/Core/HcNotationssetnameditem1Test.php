<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_notationssetnameditem1.js.
class HcNotationssetnameditem1Test extends W3CTestHarness
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
            $this->w3cAssertNotNull('docTypeNotNull', $docType);
            $notations = $docType->notations;
            $this->w3cAssertNotNull('notationsNotNull', $notations);
            $elem = $doc->createElement('br');
            try {
                $retval = $notations->setNamedItem($elem);
                $this->w3cFail('throw_HIER_OR_NO_MOD_ERR');
            } catch (DOMException $ex) {
                if (gettype($ex->code) != NULL) {
                    switch ($ex->code) {
                        case 3:
                            break;
                        case 7:
                            break;
                        default:
                            throw $ex;
                    }
                } else {
                    throw $ex;
                }
            }
        }
    }
}
