<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_notationsremovenameditem1.js.
class HcNotationsremovenameditem1Test extends W3CTestHarness
{
    public function testHcNotationsremovenameditem1()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_notationsremovenameditem1') != null) {
            return;
        }
        $doc = null;
        $notations = null;
        $docType = null;
        $retval = null;
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
            $success = false;
            try {
                $retval = $notations->removeNamedItem('notation1');
            } catch (DOMException $ex) {
                $success = gettype($ex->code) != NULL && $ex->code == 7;
            }
            $this->w3cAssertTrue('throw_NO_MODIFICATION_ALLOWED_ERR', $success);
        }
    }
}
