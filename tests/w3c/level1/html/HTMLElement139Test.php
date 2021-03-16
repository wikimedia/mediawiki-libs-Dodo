<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement139.js.
class HTMLElement139Test extends DomTestCase
{
    public function testHTMLElement139()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement139') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vclassname = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('abbr');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vclassname = $testNode->className;
        $this->assertEqualsData('classNameLink', 'ABBR-class', $vclassname);
    }
}