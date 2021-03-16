<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_domimplementationfeaturexml.js.
class HcDomimplementationfeaturexmlTest extends DomTestCase
{
    public function testHcDomimplementationfeaturexml()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_domimplementationfeaturexml') != null) {
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
            $state = $domImpl->hasFeature('html', '1.0');
            $this->assertTrueData('supports_html_1.0', $state);
        } else {
            $state = $domImpl->hasFeature('xml', '1.0');
            $this->assertTrueData('supports_xml_1.0', $state);
        }
    }
}