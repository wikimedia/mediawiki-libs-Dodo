<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/hasFeature01.js.
class HasFeature01Test extends W3CTestHarness
{
    public function testHasFeature01()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hasFeature01') != null) {
            return;
        }
        $doc = null;
        $domImpl = null;
        $version = null;
        $state = null;
        $domImpl = $this->getImplementation();
        $state = $domImpl->hasFeature('hTmL', $version);
        $this->assertTrueData('hasHTMLnull', $state);
    }
}
