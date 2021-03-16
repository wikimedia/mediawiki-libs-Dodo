<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement05.js.
class HTMLTextAreaElement05Test extends DomTestCase
{
    public function testHTMLTextAreaElement05()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement05') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcols = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vcols = $testNode->cols;
        $this->assertEqualsData('colsLink', 20, $vcols);
    }
}