<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue08.js.
class HcNodevalue08Test extends DomTestCase
{
    public function testHcNodevalue08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodevalue08') != null) {
            return;
        }
        $doc = null;
        $docType = null;
        $newNode = null;
        $newValue = null;
        $nodeMap = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->assertNotNullData('docTypeNotNull', $docType);
            $nodeMap = $docType->notations;
            $this->assertNotNullData('notationsNotNull', $nodeMap);
            $newNode = $nodeMap->getNamedItem('notation1');
            $this->assertNotNullData('notationNotNull', $newNode);
            $newValue = $newNode->nodeValue;
            $this->assertNullData('initiallyNull', $newValue);
            $newNode->nodeValue = 'This should have no effect';
            $newValue = $newNode->nodeValue;
            $this->assertNullData('nullAfterAttemptedChange', $newValue);
        }
    }
}