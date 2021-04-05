<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLIFrameElement09.js.
class HTMLIFrameElement09Test extends W3cTestHarness
{
    public function testHTMLIFrameElement09()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLIFrameElement09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'iframe');
        $nodeList = $doc->getElementsByTagName('iframe');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vsrc = $testNode->src;
        $this->assertURIEqualsData('srcLink', null, null, null, null, 'right', null, null, null, $vsrc);
    }
}
