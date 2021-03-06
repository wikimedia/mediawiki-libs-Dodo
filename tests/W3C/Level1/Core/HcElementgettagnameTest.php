<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgettagname.js.
class HcElementgettagnameTest extends W3CTestHarness
{
    public function testHcElementgettagname()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_elementgettagname') != null) {
            return;
        }
        $doc = null;
        $root = null;
        $tagname = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $root = $doc->documentElement;
        $tagname = $root->tagName;
        if ($builder->contentType == 'image/svg+xml') {
            $this->w3cAssertEquals('svgTagname', 'svg', $tagname);
        } else {
            $this->w3cAssertEqualsAutoCase('element', 'tagname', 'html', $tagname);
        }
    }
}
