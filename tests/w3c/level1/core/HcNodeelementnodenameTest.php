<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodeelementnodename.js.
class HcNodeelementnodenameTest extends DomTestCase
{
    public function testHcNodeelementnodename()
    {
        $builder = $this->getBuilder();
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