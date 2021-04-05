<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement04.js.
class HTMLTextAreaElement04Test extends W3cTestHarness
{
    public function testHTMLTextAreaElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement04') != null) {
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
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vaccesskey = $testNode->accessKey;
        $this->assertEqualsData('accessKeyLink', 'c', $vaccesskey);
    }
}
