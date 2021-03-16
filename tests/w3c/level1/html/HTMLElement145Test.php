<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement145.js.
class HTMLElement145Test extends DomTestCase
{
    public function testHTMLElement145()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement145') != null) {
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
        $nodeList = $doc->getElementsByTagName('center');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vclassname = $testNode->className;
        $this->assertEqualsData('classNameLink', 'CENTER-class', $vclassname);
    }
}