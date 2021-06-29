<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetownerdocument.js.
class HcNodegetownerdocumentTest extends W3CTestHarness
{
    public function testHcNodegetownerdocument()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodegetownerdocument') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $docNode = null;
        $ownerDocument = null;
        $docElement = null;
        $elementName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('p');
        $docNode = $elementList->item(1);
        $ownerDocument = $docNode->ownerDocument;
        $docElement = $ownerDocument->documentElement;
        $elementName = $docElement->nodeName;
        if ($builder->contentType == 'image/svg+xml') {
            $this->assertEqualsData('svgNodeName', 'svg', $elementName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'ownerDocElemTagName', 'html', $elementName);
        }
    }
}
