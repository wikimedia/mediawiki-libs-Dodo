<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_domimplementationfeaturenull.js.
class HcDomimplementationfeaturenullTest extends W3CTestHarness
{
    public function testHcDomimplementationfeaturenull()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_domimplementationfeaturenull') != null) {
            return;
        }
        $doc = null;
        $domImpl = null;
        $state = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $domImpl = $doc->implementation;
        if ($builder->contentType == 'text/html') {
            $state = $domImpl->hasFeature('HTML', null);
            $this->assertTrueData('supports_HTML_null', $state);
        } else {
            $state = $domImpl->hasFeature('XML', null);
            $this->assertTrueData('supports_XML_null', $state);
        }
    }
}
