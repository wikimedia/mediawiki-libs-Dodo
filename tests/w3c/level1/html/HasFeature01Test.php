<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/hasFeature01.js.
class HasFeature01Test extends DomTestCase
{
    public function testHasFeature01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hasFeature01') != null) {
            return;
        }
        $doc = null;
        $domImpl = null;
        $version = null;
        $state = null;
        $domImpl = getImplementation();
        $state = $domImpl->hasFeature('hTmL', $version);
        $this->assertTrueData('hasHTMLnull', $state);
    }
}