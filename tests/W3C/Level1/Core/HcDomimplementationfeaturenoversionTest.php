<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_domimplementationfeaturenoversion.js.
class HcDomimplementationfeaturenoversionTest extends W3CTestHarness
{
    public function testHcDomimplementationfeaturenoversion()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_domimplementationfeaturenoversion') != null) {
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
            $state = $domImpl->hasFeature('HTML', '');
        } else {
            $state = $domImpl->hasFeature('XML', '');
        }
        $this->w3cAssertTrue('hasFeatureBlank', $state);
    }
}
