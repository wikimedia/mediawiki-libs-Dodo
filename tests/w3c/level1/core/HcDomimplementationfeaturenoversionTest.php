<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_domimplementationfeaturenoversion.js.
class HcDomimplementationfeaturenoversionTest extends DomTestCase
{
    public function testHcDomimplementationfeaturenoversion()
    {
        $builder = $this->getBuilder();
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
        $this->assertTrueData('hasFeatureBlank', $state);
    }
}