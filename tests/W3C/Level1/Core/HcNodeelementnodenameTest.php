<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodename.js.
class HcNodeelementnodenameTest extends W3CTestHarness
{
    public function testHcNodeelementnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodeelementnodename') != null) {
            return;
        }
        $doc = null;
        $elementNode = null;
        $elementName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementNode = $doc->documentElement;
        $elementName = $elementNode->nodeName;
        if ($builder->contentType == 'image/svg+xml') {
            $this->w3cAssertEquals('svgNodeName', 'svg', $elementName);
        } else {
            $this->w3cAssertEqualsAutoCase('element', 'nodeName', 'html', $elementName);
        }
    }
}
