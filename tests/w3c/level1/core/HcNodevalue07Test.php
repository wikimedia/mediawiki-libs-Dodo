<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue07.js.
class HcNodevalue07Test extends DomTestCase
{
    public function testHcNodevalue07()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodevalue07') != null) {
            return;
        }
        $doc = null;
        $newNode = null;
        $newValue = null;
        $nodeMap = null;
        $docType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docType = $doc->doctype;
        if (!($builder->contentType == 'text/html')) {
            $this->assertNotNullData('docTypeNotNull', $docType);
            $nodeMap = $docType->entities;
            $this->assertNotNullData('entitiesNotNull', $nodeMap);
            $newNode = $nodeMap->getNamedItem('alpha');
            $this->assertNotNullData('entityNotNull', $newNode);
            $newValue = $newNode->nodeValue;
            $this->assertNullData('initiallyNull', $newValue);
            $newNode->nodeValue = 'This should have no effect';
            $newValue = $newNode->nodeValue;
            $this->assertNullData('nullAfterAttemptedChange', $newValue);
        }
    }
}