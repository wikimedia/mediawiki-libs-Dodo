<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetrootnode.js.
class HcDocumentgetrootnodeTest extends W3cTestHarness
{
    public function testHcDocumentgetrootnode()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
