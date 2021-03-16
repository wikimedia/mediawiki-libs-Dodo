<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetrootnode.js.
class HcDocumentgetrootnodeTest extends DomTestCase
{
    public function testHcDocumentgetrootnode()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentgetrootnode') != null) {
            return;
        }
        $doc = null;
        $root = null;
        $rootName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $root = $doc->documentElement;
        $rootName = $root->nodeName;
        if ($builder->contentType == 'image/svg+xml') {
            $this->assertEqualsData('svgTagName', 'svg', $rootName);
        } else {
            $this->assertEqualsAutoCaseData('element', 'docElemName', 'html', $rootName);
        }
    }
}