<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodevalue02.js.
class HcNodevalue02Test extends DomTestCase
{
    public function testHcNodevalue02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodevalue02') != null) {
            return;
        }
        $doc = null;
        $newNode = null;
        $newValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newNode = $doc->createComment('This is a new Comment node');
        $newValue = $newNode->nodeValue;
        $this->assertEqualsData('initial', 'This is a new Comment node', $newValue);
        $newNode->nodeValue = 'This should have an effect';
        $newValue = $newNode->nodeValue;
        $this->assertEqualsData('afterChange', 'This should have an effect', $newValue);
    }
}