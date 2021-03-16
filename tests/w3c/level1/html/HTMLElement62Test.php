<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement62.js.
class HTMLElement62Test extends DomTestCase
{
    public function testHTMLElement62()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement62') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlang = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('span');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vlang = $testNode->lang;
        $this->assertEqualsData('langLink', 'en', $vlang);
    }
}