<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_notationsremovenameditem1.js.
class HcNotationsremovenameditem1Test extends DomTestCase
{
    public function testHcNotationsremovenameditem1()
    {
        $builder = $this->getBuilder();
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
            $this->assertNotNullData('docTypeNotNull', $docType);
            $notations = $docType->notations;
            $this->assertNotNullData('notationsNotNull', $notations);
            $success = false;
            try {
                $retval = $notations->removeNamedItem('notation1');
            } catch (DomException $ex) {
                $success = gettype($ex->getCode()) != NULL && $ex->getCode() == 7;
            }
            $this->assertTrueData('throw_NO_MODIFICATION_ALLOWED_ERR', $success);
        }
    }
}