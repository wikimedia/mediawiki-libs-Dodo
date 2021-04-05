<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLLabelElement03.js.
class HTMLLabelElement03Test extends W3cTestHarness
{
    public function testHTMLLabelElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLLabelElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaccesskey = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'label');
        $nodeList = $doc->getElementsByTagName('label');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vaccesskey = $testNode->accessKey;
        $this->assertEqualsData('accesskeyLink', 'b', $vaccesskey);
    }
}
