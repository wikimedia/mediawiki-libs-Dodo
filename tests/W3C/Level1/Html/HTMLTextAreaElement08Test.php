<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement08.js.
class HTMLTextAreaElement08Test extends W3CTestHarness
{
    public function testHTMLTextAreaElement08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vreadonly = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(2);
        $vreadonly = $testNode->readOnly;
        $this->assertTrueData('readOnlyLink', $vreadonly);
    }
}
