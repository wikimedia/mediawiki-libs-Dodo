<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement08.js.
class HTMLTextAreaElement08Test extends DomTestCase
{
    public function testHTMLTextAreaElement08()
    {
        $builder = $this->getBuilder();
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