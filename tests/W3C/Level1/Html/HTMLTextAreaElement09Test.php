<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement09.js.
class HTMLTextAreaElement09Test extends W3CTestHarness
{
    public function testHTMLTextAreaElement09()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vrows = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->w3cAssertSize('Asize', 3, $nodeList);
        $testNode = $nodeList->item(0);
        $vrows = $testNode->rows;
        $this->w3cAssertEquals('rowsLink', 7, $vrows);
    }
}
