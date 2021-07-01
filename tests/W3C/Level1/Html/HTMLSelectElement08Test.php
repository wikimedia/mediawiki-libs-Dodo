<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement08.js.
class HTMLSelectElement08Test extends W3CTestHarness
{
    public function testHTMLSelectElement08()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLSelectElement08') != null) {
            return;
        }
        $nodeList = null;
        $optionsnodeList = null;
        $testNode = null;
        $vareas = null;
        $doc = null;
        $optionName = null;
        $voption = null;
        $result = [];
        $expectedOptions = [];
        $expectedOptions[0] = 'option';
        $expectedOptions[1] = 'option';
        $expectedOptions[2] = 'option';
        $expectedOptions[3] = 'option';
        $expectedOptions[4] = 'option';
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->w3cAssertSize('Asize', 3, $nodeList);
        $testNode = $nodeList->item(0);
        $optionsnodeList = $testNode->options;
        for ($indexN65648 = 0; $indexN65648 < count($optionsnodeList); $indexN65648++) {
            $voption = $optionsnodeList->item($indexN65648);
            $optionName = $voption->nodeName;
            $result[count($result)] = $optionName;
        }
        $this->w3cAssertEqualsListAutoCase('element', 'optionsLink', $expectedOptions, $result);
    }
}
