<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodename.js.
class HcNodeelementnodenameTest extends W3cTestHarness
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
            $this->assertEqualsData('svgNodeName', 'svg', $elementName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'nodeName', 'html', $elementName);
        }
    }
}
