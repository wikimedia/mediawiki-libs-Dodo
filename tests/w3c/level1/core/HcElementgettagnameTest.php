<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_elementgettagname.js.
class HcElementgettagnameTest extends DomTestCase
{
    public function testHcElementgettagname()
    {
        $builder = $this->getBuilder();
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
            $this->assertEqualsData('svgTagname', 'svg', $tagname);
        } else {
            $this->assertEqualsAutoCaseData('element', 'tagname', 'html', $tagname);
        }
    }
}