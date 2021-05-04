<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodevalue.js.
class HcNodeelementnodevalueTest extends W3cTestHarness
{
    public function testHcNodeelementnodevalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeelementnodevalue') != null) {
            return;
        }
        $doc = null;
        $elementNode = null;
        $elementValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementNode = $doc->documentElement;
        $elementValue = $elementNode->nodeValue;
        $this->assertNullData('elementNodeValue', $elementValue);
    }
}
