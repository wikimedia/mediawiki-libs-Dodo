<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_domimplementationfeaturenull.js.
class HcDomimplementationfeaturenullTest extends W3cTestHarness
{
    public function testHcDomimplementationfeaturenull()
    {
        $builder = $this->getBuilder();
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
