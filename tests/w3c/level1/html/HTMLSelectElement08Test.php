<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement08.js.
class HTMLSelectElement08Test extends DomTestCase
{
    public function testHTMLSelectElement08()
    {
        $builder = $this->getBuilder();
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
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $optionsnodeList = $testNode->options;
        for ($indexN65648 = 0; $indexN65648 < count($optionsnodeList); $indexN65648++) {
            $voption = $optionsnodeList->item($indexN65648);
            $optionName = $voption->nodeName;
            $result[count($result)] = $optionName;
        }
        $this->assertEqualsListAutoCaseData('element', 'optionsLink', $expectedOptions, $result);
    }
}