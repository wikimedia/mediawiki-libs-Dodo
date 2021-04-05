<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLAreaElement;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement03.js.
class HTMLAreaElement03Test extends W3cTestHarness
{
    public function testHTMLAreaElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcoords = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcoords = $testNode->coords;
        $this->assertEqualsData('coordsLink', '0,2,45,45', $vcoords);
    }
}
