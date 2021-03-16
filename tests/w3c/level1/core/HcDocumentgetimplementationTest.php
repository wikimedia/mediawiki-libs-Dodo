<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentgetimplementation.js.
class HcDocumentgetimplementationTest extends DomTestCase
{
    public function testHcDocumentgetimplementation()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentgetimplementation') != null) {
            return;
        }
        $doc = null;
        $docImpl = null;
        $xmlstate = null;
        $htmlstate = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $docImpl = $doc->implementation;
        $xmlstate = $docImpl->hasFeature('XML', '1.0');
        $htmlstate = $docImpl->hasFeature('HTML', '1.0');
        if ($builder->contentType == 'text/html') {
            $this->assertTrueData('supports_HTML_1.0', $htmlstate);
        } else {
            $this->assertTrueData('supports_XML_1.0', $xmlstate);
        }
    }
}